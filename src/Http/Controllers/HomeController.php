<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Mail\MessageMail;
use Bishopm\Church\Models\Attendance;
use Bishopm\Church\Models\Book;
use Bishopm\Church\Models\Cache;
use Bishopm\Church\Models\Comment;
use Bishopm\Church\Models\Devotional;
use Bishopm\Church\Models\Document;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Loan;
use Bishopm\Church\Models\Person;
use Bishopm\Church\Models\Post;
use Bishopm\Church\Models\Project;
use Bishopm\Church\Models\Series;
use Bishopm\Church\Models\Sermon;
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
use Spatie\Tags\Tag;
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
        $today=date('Y-m-d');
        $data['content']=array();
        $monthago=date('Y-m-d',strtotime('-80 days'));      
        $sermons=Sermon::where('servicedate','>',$monthago)->orderBy('servicedate','DESC')->get();
        foreach ($sermons as $sermon){
            $data['content'][strtotime($sermon->servicedate)]=$sermon;
        }
        $blogs=Post::where('published_at','>',$monthago)->orderBy('published_at','DESC')->get();
        foreach ($blogs as $blog){
            $data['content'][strtotime($blog->published_at)]=$blog;
        }
        $devs=Devotional::where('publicationdate','>',$monthago)->orderBy('publicationdate','DESC')->get();
        foreach ($devs as $dev){
            $data['content'][strtotime($dev->publicationdate)]=$dev;
        }
        $data['service']=Service::withWhereHas('setitems', function($q) { $q->where('setitemable_type','song'); })->where('servicedate','>=',$today)->where('livestream','1')->orderBy('servicedate','ASC')->first();
        if ($data['service']){
            $floor = floor((strtotime($data['service']->servicedate) - time())/3600/24);
            if ($floor == 1){
                $data['floor'] = "1 day";
            } else {
                $data['floor'] = $floor . " days";
            }
            $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $data['service']->servicetime . "/" . substr($data['service']->servicedate,0,10);
            $response=Http::get($url);
            $data['preacher']=$response->body();
        }
        krsort($data['content']);
        return view('church::app.home',$data);
    }

    public function blogpost($yr,$mth,$slug){
        $data['post']=Post::where('slug',$slug)->first();
        $relatedBlogs=Post::withAnyTags($data['post']->tags)->where('slug','<>',$slug)->where('published',1)->orderBy('published_at','DESC')->get();
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
        $data['olddate']="";
        $data['full']=$full;
        return view('church::' . $this->routeName . '.calendar',$data);
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

    public function details(){
        $data['indiv']=Individual::find($this->member['id']);
        return view('church::app.details',$data);
    }

    public function devotionals(){
        $today=date('Y-m-d');
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
        $prayers=Devotional::orderBy('publicationdate','DESC')->get()->take(5);
        foreach ($prayers as $prayer){
            $cache=Cache::where('title',$prayer->reading)->first();
            if (!$cache){
                $body="Dummy text";
            } else {
                $body=$cache->body;
            }
            $prayer->body=$body;
            $data['prayers'][] = $prayer;
        }
        return view('church::app.devotionals',$data);
    }

    public function giving(){
        return view('church::' . $this->routeName . '.giving');
    }

    public function group($id){
        $data['group']=Group::with('individual')->where('id',$id)->first();
        return view('church::' . $this->routeName . '.group',$data);
    }

    public function groups(){
        $data['groups']=Group::where('grouptype','fellowship')->orderBy('groupname')->where('publish',1)->get();
        return view('church::' . $this->routeName . '.groups',$data);
    }

    public function home(FormRequest $request)
    {
        if (null!==$request->input('message')){
            $data['message'] = $request->input('message');
            $data['user'] = $request->input('user');
            Mail::to('michael@westvillemethodist.co.za')->queue(new MessageMail($data));
            $data['notification']="Thank you! We will reply to you by email";
        }
        $data['blogs']=Post::with('person')->where('published',1)->orderBy('published_at','DESC')->take(3)->get();
        $data['sermon']=Sermon::with('person','series')->where('published',1)->orderBy('servicedate','DESC')->first();
        $data['pageName'] = "Home";
        return view('church::web.home',$data);
    }

    public function login(){
        return view('church::app.login');
    }

    public function offline(){
        return view('church::app.offline');
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
        $data['person']=Person::with('sermons','posts')->where('slug',$slug)->first();
        return view('church::' . $this->routeName . '.person',$data);
    }

    public function project($id){
        $data['project']=Project::find($id);
        return view('church::' . $this->routeName . '.project',$data);
    }

    public function projects(){
        $data['projects']=Project::orderBy('project')->paginate(10);
        return view('church::' . $this->routeName . '.projects',$data);
    }

    public function quietmoments() {
        $data['scs']=Document::where('category','quiet-moments')->orderBy('created_at','DESC')->simplePaginate(15);
        return view('church::web.quietmoments',$data);
    }

    public function series($year,$slug){
        $data['series']=Series::with('sermons.person')->where('slug',$slug)->first();
        return view('church::' . $this->routeName . '.series',$data);
    }
    
    public function sermons() {
        $data['series']=Series::with('sermons')->orderBy('startingdate','DESC')->paginate(10);
        return view('church::' . $this->routeName . '.sermons',$data);
    }

    public function sermon($year,$slug, $id){
        $data['sermon']=Sermon::with('person')->where('id',$id)->first();
        $data['series']=Series::with('sermons')->where('id',$data['sermon']->series_id)->first();
        return view('church::' . $this->routeName . '.sermon',$data);
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
        $data['tag']=Tag::findFromString($slug);
        $data['posts']=Post::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['sermons']=Sermon::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['books']=Book::withAnyTags($data['tag']->name)->get();
        return view('church::' . $this->routeName . '.tag',$data);
    }

    public function sunday()
    {
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
    

}
