<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Classes\YoutubeService;
use Bishopm\Church\Events\NewLiveMessage;
use Bishopm\Church\Mail\MessageMail;
use Bishopm\Church\Models\Attendance;
use Bishopm\Church\Models\Book;
use Bishopm\Church\Models\Cache;
use Bishopm\Church\Models\Card;
use Bishopm\Church\Models\Comment;
use Bishopm\Church\Models\Course;
use Bishopm\Church\Models\Coursesession;
use Bishopm\Church\Models\Document;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Loan;
use Bishopm\Church\Models\Page;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastoralnote;
use Bishopm\Church\Models\Person;
use Bishopm\Church\Models\Post;
use Bishopm\Church\Models\Project;
use Bishopm\Church\Models\Rosteritem;
use Bishopm\Church\Models\Series;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Song;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as FormRequest;
use Illuminate\Support\Facades\Mail;
use Spatie\GoogleCalendar\Event;
use Bishopm\Church\Models\Event as ChurchEvent;
use Bishopm\Church\Models\Roster;
use Bishopm\Church\Models\Tag;
use Vedmant\FeedReader\Facades\FeedReader;

class HomeController extends Controller
{

    public $member, $routeName;

    public function __construct(){
        $this->member=Config::get('member');
        $routename = Request::route()->getName();
        if (str_contains($routename,'app.')){
            $this->routeName="app";
        } else {
            $this->routeName="web";
        }
    }

     /**
     * Display a listing of the resource.
     */
    
    public function app(){
        $settings=$this->member['app']['Home page content'];
        $today=date('Y-m-d');
        $data['content']=array();
        $monthago=date('Y-m-d',strtotime('-31 days'));
        if ($settings['Services']==true){
            $sermons=Service::where('servicedate','>',$monthago)->where('servicedate','<',$today)->whereNotNull('audio')->orderBy('servicedate','DESC')->get();
            foreach ($sermons as $sermon){
                $data['content'][strtotime($sermon->servicedate)]=$sermon;
            }
        }
        if ($settings['Blog posts']==true){
            $blogs=Post::where('published_at','>',$monthago)->orderBy('published_at','DESC')->get();
            foreach ($blogs as $blog){
                $data['content'][strtotime($blog->published_at)]=$blog;
            }
        }
        $soon=date('Y-m-d',strtotime('+10 days'));
        $courses=Course::withWhereHas('coursesessions', function ($q) use($soon,$today) { $q->where('sessiondate','>',$soon)->where('sessiondate','<',$today);})->orderBy('course','ASC')->get();
        foreach ($courses as $course){
            $data['content'][$course->course]=$course;
        }
        $events=ChurchEvent::where('eventdate','>',$today)->where('eventdate','<',$soon)->orderBy('eventdate','ASC')->get();
        foreach ($events as $event){
            $data['content'][strtotime($event->eventdate)]=$event;
        }
        $data['service']=Service::with('person')->withWhereHas('setitems', function($q) { $q->where('setitemable_type','song')->orderBy('sortorder'); })->where('servicedate','>=',$today)->whereNotNull('video')->orderBy('servicedate','ASC')->first();
        if ($data['service']){
            $floor = floor((strtotime($data['service']->servicedate) - time())/3600/24);
            if ($floor == 1){
                $data['floor'] = "1 day";
            } else {
                $data['floor'] = $floor . " days";
            }
        }
        krsort($data['content']);
        return view('church::app.home',$data);
    }

    public function blogpost($yr,$mth,$slug){
        $data['post']=Post::where(DB::raw('substr(published_at, 1, 4)'), '=',$yr)->where(DB::raw('substr(published_at, 6, 2)'), $mth)->where('slug',$slug)->first();
        $relatedBlogs=Post::withTags($data['post']->tags)->where('id','<>',$data['post']->id)->where('published',1)->orderBy('published_at','DESC')->get();
        $related=array();
        foreach ($relatedBlogs as $blog){
            $dum=array();
            $dum['title'] = $blog->title;
            $dum['slug'] = $blog->slug;
            $dum['published_at'] = $blog->published_at;
            $related['blogs'][date('Y',strtotime($blog->published_at))][]=$dum;
        }
        $data['related']=$related;

        return view('church::' . $this->routeName . '.blogpost',$data);
    }

    public function blog() {
        $data['posts']=Post::orderBy('published_at','DESC')->paginate(10);
        return view('church::' . $this->routeName . '.blog',$data);
    }

    public function blogger($slug) {
        $blogger=Person::where('slug',$slug)->first();
        if ($blogger){
            $data['posts']=Post::where('person_id',$blogger->id)->orderBy('published_at','DESC')->paginate(10);
            return view('church::' . $this->routeName . '.blogger',$data);
        } else {
            abort(404);
        }
    }

    public function book($id){
        $data['book']=Book::with('comments')->where('id',$id)->first();
        if (isset($this->member->id)){
            $data['comment']=Comment::where('book_id',$id)->where('individual_id',$this->member->id)->first();
        }
        return view('church::' . $this->routeName . '.book',$data);
    }

    public function books(){
        if (isset($_GET['search'])){
            $data['search']=$_GET['search'];
        } else {
            $data['search']="";
        }
        if ($data['search']<>""){
            $data['books']=Book::where('title','like','%' . $data['search'] . '%')->orWhere('authors','like','%' . $data['search'] . '%')->orderBy('title')->paginate(15);
        } else {
            $data['books']=Book::orderBy('title')->paginate(15);
        }
        $data['books']->appends(['search' => $data['search']]);
        return view('church::' . $this->routeName . '.books',$data);
    }

    public function calendar($full=""){
        if ($this->routeName=="web"){
            $today=date('Y-m-d');
            $events=Event::get(new Carbon($today),new Carbon(date('Y-12-31',strtotime('+1 year'))));
            foreach ($events as $event){
                $me=$this->calendar_attend($event->description);
                if (($full=="yes") or ($me=="yes")){
                    if (is_null($event->startDateTime)){
                        $data['events'][date('Y-m-d',strtotime($event->startDate))][]=[
                            'name' => $event->name,
                            'time' => "",
                            'me' => $me
                        ];
                    } else {
                        $data['events'][date('Y-m-d',strtotime($event->startDateTime))][]=[
                            'name' => $event->name,
                            'time' => date('H:i',strtotime($event->startDateTime)),
                            'me' => $me
                        ];
                    }
                }
            }
            $data['full']=$full;
            return view('church::web.calendar',$data);
        } else {
            return view('church::app.calendar');
        }
    }

    private function calendar_attend($description){
        if (str_contains($description,'group_id')){
            $id=substr($description,9);
            if ($id==0){
                return "yes";
            } else {
                $result=DB::table('group_individual')
                ->where('individual_id', $this->member['id'])
                ->where('group_id',$id)
                ->get();
                if (count($result)==0){
                    return "no";
                } else {
                    return "yes";
                }
            }
        } else {
            return "no";
        }
    }

    public function contact(){
        return view('church::' . $this->routeName . '.contact');
    }

    public function course($id){
        $data['course']=Course::with('coursesessions')->find($id);
        return view('church::' . $this->routeName . '.course',$data);
    }

    public function courses(){
        $today=date('Y-m-d');
        $courses=Course::with('coursesessions')->orderBy('course')->get();
        $data['courses']=array();
        $data['courses']['upcoming']=array();
        $data['courses']['library']=array();
        foreach ($courses as $course){
            if (count($course->coursesessions)){
                $thisdate=$course->coursesessions[0]->sessiondate;
            } else {
                $thisdate="";
            }
            if ($thisdate >= date('Y-m-d')){
                $data['courses']['upcoming'][strtotime($thisdate)][]=$course;
            } else {
                $data['courses']['library'][$course->course]=$course;
            }
        }
        ksort($data['courses']['upcoming']);
        ksort($data['courses']['library']);
        return view('church::' . $this->routeName . '.courses',$data);
    }

    public function details(){
        $data['indiv']=Individual::find($this->member['id']);
        return view('church::app.details',$data);
    }

    public function devotionals(){
        $today=date('Y-m-d');
        $data['settings']=$this->member['app']['Devotional'];
        ksort($data['settings']);
        $data['active']="";
        foreach ($data['settings'] as $cat=>$setting){
            if ($data['settings'][$cat]==true){
                $data['active']=$cat;
                break;
            }
        }
        if ($data['settings']['Faith for daily living']){
            $ffdl=Cache::where('category','FFDL')->where(DB::raw('SUBSTRING(created_at,1,10)'),$today)->first();
            if (!$ffdl){
                $f = FeedReader::read('https://ffdl.co.za/feed/');
                $ffdl=Cache::create([
                    'category'=>'FFDL',
                    'title'=>$f->get_items()[0]->get_title(),
                    'body'=>$f->get_items()[0]->get_content()
                ]);
            }
            $data['ffdl']=$ffdl->body;
            $data['ffdl_title']=$ffdl->title;
        }
        if ($data['settings']['Quiet moments']){
            $data['quiets']=Document::where('category','quiet-moments')->orderBy('created_at','DESC')->paginate(20);
        }
        return view('church::app.devotionals',$data);
    }

    public function event($id){
        $data['event']=ChurchEvent::find($id);
        return view('church::' . $this->routeName . '.event',$data);
    }

    public function events(){
        $today=date('Y-m-d');
        $data['events']=ChurchEvent::orderBy('eventdate')->where('eventdate','>=',$today)->get();
        return view('church::' . $this->routeName . '.events',$data);
    }

    public function find(){
        return view('church::app.find');
    }

    public function group($id){
        $data['group']=Group::with('individual')->where('id',$id)->first();
        return view('church::' . $this->routeName . '.group',$data);
    }

    public function groups(){
        $groups=Group::where('grouptype','fellowship')->orderBy('meetingday')->orderBy('meetingtime')->where('publish',1)->get();
        $data['days'] = [0 => 'Sunday',1 => 'Monday',2 => 'Tuesday',3 => 'Wednesday',4 => 'Thursday',5 => 'Friday',6 => 'Saturday'];
        foreach ($groups as $group){
            if (isset($group->meetingday)){
                $data['groups'][$group->meetingday][]=$group;
            } else {
                $data['groups']['No day'][]=$group;
            }
        }
        return view('church::' . $this->routeName . '.groups',$data);
    }

    public function home(FormRequest $request)
    {
        $today=date('Y-m-d');
        if (null!==$request->input('message')){
            $data['message'] = $request->input('message');
            $data['user'] = $request->input('user');
            Mail::to(setting('email.church_email'))->queue(new MessageMail($data));
            $data['notification']="Thank you! We will reply to you by email";
        }
        $data['blogs']=Post::with('person')->where('published',1)->orderBy('published_at','DESC')->take(3)->get();
        $data['upcoming']=Service::withWhereHas('setitems', function($q) { $q->where('setitemable_type','song'); })->where('servicedate','>=',$today)->whereNotNull('video')->orderBy('servicedate','ASC')->first();
        $data['sermon']=Service::with('person','series')->whereNotNull('audio')->orderBy('servicedate','DESC')->first();
        $data['pageName'] = "Home";
        return view('church::web.home',$data);
    }

    public function login(){
        return view('church::app.login');
    }

    public function offline(){
        return view('church::app.offline');
    }

    public function path($url){
        $data['card']=Card::with('carditems')->where('url',$url)->first();
        return view('church::app.path',$data);
    }

    public function page($page){
        $data['page']=Page::where('slug',$page)->where('published',1)->firstOrFail();
        return view('church::' . $this->routeName . '.page',$data);
    }

    public function pastoral(){
        $data['pastor']=Pastor::with('individual')->where('individual_id',$_COOKIE['wmc-id'])->first();
        $data['my_cases']=Pastor::with('individuals','households')->where('id',$data['pastor']->id)->first();
        $all_cases=Pastor::with('individuals','households')->where('id','<>',$data['pastor']->id)->get();
        $data['all_cases']['individuals']=array();
        $data['all_cases']['households']=array();
        foreach ($all_cases as $pastor){
            foreach ($pastor->individuals as $indiv){
                if ($indiv->pivot->active){
                    $data['all_cases']['individuals'][$indiv->firstname.$indiv->surname]=$indiv;
                }
            }
            foreach ($pastor->households as $hld){
                if ($hld->pivot->active){
                    $data['all_cases']['households'][$hld->sortsurname]=$hld;
                }
            }
        }
        ksort($data['all_cases']['individuals']);
        ksort($data['all_cases']['households']);
        return view('church::app.pastoral',$data);
    }

    public function pastoralcase($type,$id){
        $data['pastor']=Pastor::where('individual_id',$_COOKIE['wmc-id'])->first();
        if ($type=="household"){
            $data['case']=Household::with('pastors.individual','pastoralnotes')
            ->orderBy(Pastoralnote::select('pastoraldate')->whereColumn('pastoralnotable_id','households.id')->orderByDesc('pastoraldate'))->find($id);
            $data['name']=$data['case']->addressee;
        } else {
            $data['case']=Individual::with(['pastors.individual','pastoralnotes'=>function ($q){$q->orderBy('pastoraldate','DESC');}])->where('id',$id)->first();
            $data['name']=$data['case']->firstname;
        }
        $pastors=$data['case']->pastors;
        foreach ($pastors as $pastor){
            $pastorids[]=$pastor->id;
        }
        if (in_array($pastor->id,$pastorids)){
            $data['detail']=1;
        } else {
            $data['detail']=0;
        }
        $data['pastoralnotable_type']=$type;
        $data['mostrecent']=Pastoralnote::with('pastoralnotable')->whereHas('pastoralnotable', function($q) use($type,$id) {
            $q->where('pastoralnotable_id', $id)->where('pastoralnotable_type',$type);})->orderBy('pastoraldate','DESC')->first();
        $data['pastoraldate']=date('Y-m-d');
        return view('church::app.pastoralcase',$data);
    }

    public function practices(){
        $data=array();
        $data['indiv']=Individual::find($this->member['id']);
        $data['servicegroups']=Group::where('grouptype','service')->whereHas('individuals', function ($q) {
            $q->where('individuals.id',$this->member['id']); })->get();
        $data['fellowship']=Group::where('grouptype','fellowship')->whereHas('individuals', function ($q) {
            $q->where('individuals.id',$this->member['id']); })->get();
        $lastyear=date('Y-m-d',strtotime('-1 year'));
        $data['giving']=Gift::where('pgnumber',$data['indiv']->giving)->where('paymentdate','>=',$lastyear)->get();
        $worships=Attendance::where('individual_id',$this->member['id'])->where('attendancedate','>=',$lastyear)->get();
        $data['loans']=Loan::with('book')->where('individual_id',$this->member['id'])->whereNull('returndate')->get();
        $data['worship']=array();
        foreach ($worships as $worship){
            if (isset($data['worship'][$worship->service])){
                $data['worship'][$worship->service]++;
            } else {
                $data['worship'][$worship->service]=1;
            }
        }
        ksort($data['worship']);
        $today=date('Y-m-d');
        $roster=Individual::with('rosteritems.rostergroup.group')->where('id',$this->member['id'])->first();
        $data['roster']=array();
        foreach ($roster->rosteritems as $ri){
            if ($ri->rosterdate>$today){
                $data['roster'][$ri->rosterdate][]=$ri->rostergroup->group->groupname;
            }
        }
        ksort($data['roster']);
        return view('church::app.practices',$data);
    }

    public function person($slug){
        $data['person']=Person::with('services','posts')->where('slug',$slug)->first();
        return view('church::' . $this->routeName . '.person',$data);
    }

    public function project($slug){
        $data['project']=Project::where('slug',$slug)->first();
        return view('church::' . $this->routeName . '.project',$data);
    }

    public function projects(){
        $data['projects']=Project::orderBy('project')->where('active',1)->paginate(10);
        return view('church::' . $this->routeName . '.projects',$data);
    }

    public function quietmoments() {
        $data['scs']=Document::where('category','quiet-moments')->orderBy('created_at','DESC')->simplePaginate(15);
        return view('church::web.quietmoments',$data);
    }
    
    public function roster($slug){
        $nextweek=date('Y-m-d',strtotime('+ 1 week'));
        $today=date('Y-m-d');
        $services = setting('general.services');
        $group=Group::with('rostergroups.roster')->where('slug',$slug)->first();
        $data['message']="";
        if (!$group){
            // Service-based groups
            $data['group']=ucwords(str_replace('-', ' ', $slug)); 
            foreach ($services as $service){
                $group=Group::with('rostergroups.roster')->where('slug',$slug . "-" . $service)->first();
                if ($group){
                    foreach ($group->rostergroups as $rg){
                        $data['rosters'][$rg->roster->roster]=Rosteritem::with('individuals')->where('rostergroup_id',$rg->id)->where('rosterdate','<',$nextweek)->where('rosterdate','>',$today)->get();
                    }
                    if (isset($data['rosters'])){
                        ksort($data['rosters']);
                    }
                } else {
                    $data['message']="Sorry, there are no details for this group";
                }
            }
        } else {
            // Single group
            $data['group']=$group->groupname;
            foreach ($group->rostergroups as $rg){
                $data['rosters'][$rg->roster->roster]=Rosteritem::with('individuals')->where('rostergroup_id',$rg->id)->where('rosterdate','<',$nextweek)->where('rosterdate','>',$today)->get();
            }
            if (isset($data['rosters'])){
                ksort($data['rosters']);
            }
        }
        return view('church::web.roster',$data);
    }

    public function rosterdates(){
        $data['servicegroups']=Group::where('grouptype','service')->whereHas('individuals', function ($q) {
            $q->where('individuals.id',$this->member['id']); })->get();
        $today=date('Y-m-d');
        $roster=Individual::with('rosteritems.rostergroup.group')->where('id',$this->member['id'])->first();
        $data['roster']=array();
        foreach ($roster->rosteritems as $ri){
            if ($ri->rostergroup){
                $ss=Roster::find($ri->rostergroup->roster_id)->sundayservice;
                if ($ri->rosterdate>$today){
                    $gn=$ri->rostergroup->group->groupname;
                    if ($ss){
                        $gn.= " (" . $ss . ")";
                    } 
                    $data['roster'][$ri->rosterdate][]=$gn;
                }
            }
        }
        ksort($data['roster']);
        return view('church::app.myroster',$data);
    }

    public function series($year,$slug){
        $data['series']=Series::withWhereHas('services', function ($q) { $q->whereNotNull('audio')->whereNotNull('video');})->where('slug',$slug)->first();
        return view('church::' . $this->routeName . '.series',$data);
    }
    
    public function sermons() {
        $data['series']=Series::withWhereHas('services', function ($q) { $q->whereNotNull('audio')->whereNotNull('video');})->orderBy('startingdate','DESC')->paginate(10);
        return view('church::' . $this->routeName . '.sermons',$data);
    }

    public function sermon($year,$slug, $id){
        $data['sermon']=Service::with('person')->whereNotNull('audio')->whereNotNull('video')->where('id',$id)->first();
        if (isset($data['sermon']->series_id)){
            $data['series']=Series::withWhereHas('services', function ($q) { $q->whereNotNull('audio')->whereNotNull('video')->orderByDesc('servicedate');})->where('id',$data['sermon']->series_id)->first();
        }
        return view('church::' . $this->routeName . '.sermon',$data);
    }

    public function session($id, $session){
        $data['session']=Coursesession::with('course')->where('id',$session)->first();
        return view('church::' . $this->routeName . '.session',$data);
    }

    public function settings() {
        $data['id']=$this->member['id'];
        if ($this->member['app']){
            $data['settings']=$this->member['app'];
        } else {
            $data['settings']=[];
        }
        return view('church::app.settings',$data);
    }

    public function song($id) {
        $song=Song::find($id);
        $song->lyrics=preg_replace('/\{[a-zA-Z0-9_]+?\}/','',$song->lyrics);
        $data['song']=$song;
        return view('church::app.song',$data);
    }

    public function songs() {
        if (isset($_GET['search'])){
            $data['search']=$_GET['search'];
        } else {
            $data['search']="";
        }
        if ($data['search']<>""){
            $data['songs']=Song::whereHas('setitem')->where('title','like','%' . $data['search'] . '%')->orWhere('author','like','%' . $data['search'] . '%')->orderBy('title','ASC')->select('id','title','author')->simplePaginate(15);
        } else {
            $data['songs']=Song::whereHas('setitem')->orderBy('title','ASC')->select('id','title','author')->simplePaginate(15);
        }
        $data['songs']->appends(['search' => $data['search']]);
        return view('church::app.songs',$data);
    }

    public function stayingconnected() {
        $data['scs']=Document::where('category','staying-connected')->orderBy('created_at','DESC')->simplePaginate(15);
        return view('church::web.stayingconnected',$data);
    }

    public function subject($slug){
        $data['tag']=Tag::unslug($slug);
        $data['posts']=Post::withTag($data['tag'])->where('published',1)->get();
        $data['sermons']=Service::withTag($data['tag'])->where('published',1)->get();
        $data['books']=Book::withTag($data['tag'])->get();
        return view('church::' . $this->routeName . '.tag',$data);
    }

    public function sunday(){
        $sunday=date('l j F Y');
        // $sunday="Sunday 30 July 2023";
        $servicetime='09h00';
        $set = Service::where('servicedate',date('Y-m-d',strtotime($sunday)))->where('servicetime',$servicetime)->first();
        if ($set){
            $reading = $set->reading;
            $url="https://methodist.church.net.za/preacher/697/" . $set->servicetime . "/" . substr($set->servicedate,0,10);
            $response=Http::get($url);
            if (isset($response)){
                $preacher="Preacher: " . $response->body();
            } else {
                $preacher = "";
            }
            $txt="<div style=\"background-color: rgba(0, 0, 0, .3); text-align:center; color:white; font-family:Arial;\"><br><h3>" . $sunday . "</h3><h3>Reading: " . $reading . "</h3><h3>" . $preacher . "</h3><br></div>";
        } else {
            $txt="<div style=\"background-color: rgba(0, 0, 0, .3); text-align:center; color:white; font-family:Arial;\"><br><h3>" . $sunday . "</h3><br></div>";
        }
        return $txt;
    }

    public function team($id){
        $data['team']=Group::find($id);
        return view('church::' . $this->routeName . '.team',$data);
    }

    public function teams() {
        $teams=Group::where('grouptype','service')->where('publish',1)->orderBy('groupname','ASC')->get();
        $services=setting('general.services');
        foreach ($teams as $team){
            $sflag=false;
            foreach ($services as $service){
                if (str_contains($team->groupname,$service)){
                    $groupname=trim(str_replace($service,'',$team->groupname)," ");
                    $data['teams'][$groupname]['services'][$service]=$team;
                    $sflag=true;
                }
            }
            if (!$sflag){
                $data['teams'][$team->groupname]['teams']=$team;
            }
        }
        return view('church::' . $this->routeName . '.teams',$data);
    }

}
