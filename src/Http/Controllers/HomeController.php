<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Models\Attendance;
use Bishopm\Church\Models\Book;
use Bishopm\Church\Models\Comment;
use Bishopm\Church\Models\Devotional;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Loan;
use Bishopm\Church\Models\Person;
use Bishopm\Church\Models\Post;
use Bishopm\Church\Models\Project;
use Bishopm\Church\Models\Series;
use Bishopm\Church\Models\Sermon;
use Illuminate\Support\Facades\Config;
use Spatie\Tags\Tag;

class HomeController extends Controller
{

    public $member;

    public function __construct(){
        $this->member=Config::get('member');
    }

     /**
     * Display a listing of the resource.
     */
    
    public function app(){
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
        krsort($data['content']);
        return view('church::app.home',$data);
    }
    
    public function home()
    {
        $data['blogs']=Post::with('person')->where('published',1)->orderBy('published_at','DESC')->take(3)->get();
        $data['sermon']=Sermon::with('person','series')->where('published',1)->orderBy('servicedate','DESC')->first();
        $data['pageName'] = "Home";
        return view('church::website.home',$data);
    }

    public function login(){
        return view('church::website.login');
    }

    public function blogpost($yr,$mth,$slug,$mode="website"){
        $data['post']=Post::where('slug',$slug)->first();
        $relatedBlogs=Post::withAnyTags($data['post']->tags)->where('published',1)->orderBy('published_at','DESC')->get();
        $related=array();
        foreach ($relatedBlogs as $blog){
            $dum=array();
            $dum['title'] = $blog->title;
            $dum['slug'] = $blog->slug;
            $dum['published_at'] = $blog->published_at;
            $related['blogs'][date('Y',strtotime($blog->published_at))][]=$dum;
        }
        $data['related']=$related;
        return view('church::' . $mode . '.blogpost',$data);
    }

    public function blog($mode="website") {
        $data['posts']=Post::orderBy('published_at','DESC')->paginate(10);
        return view('church::' . $mode . '.blog',$data);
    }

    public function blogger($slug,$mode="website") {
        $blogger=Person::where('slug',$slug)->first();
        $data['posts']=Post::where('person_id',$blogger->id)->orderBy('published_at','DESC')->paginate(10);
        return view('church::' . $mode . '.blogger',$data);
    }

    public function subject($slug,$mode="website"){
        $data['tag']=Tag::findFromString($slug);
        $data['posts']=Post::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['sermons']=Sermon::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['books']=Book::withAnyTags($data['tag']->name)->get();
        return view('church::' . $mode . '.tag',$data);
    }

    public function book($id,$mode="website"){
        $data['book']=Book::with('comments')->where('id',$id)->first();
        if (isset($this->member->id)){
            $data['comment']=Comment::where('book_id',$id)->where('individual_id',$this->member->id)->first();
        }
        return view('church::' . $mode . '.book',$data);
    }

    public function books($mode="website"){
        $data['books']=Book::orderBy('title')->paginate(10);
        return view('church::' . $mode . '.books',$data);
    }

    public function giving($mode="website"){
        return view('church::' . $mode . '.giving');
    }

    public function group($id,$mode="website"){
        $data['group']=Group::with('individual')->where('id',$id)->first();
        return view('church::' . $mode . '.group',$data);
    }

    public function groups($mode="website"){
        $data['groups']=Group::where('grouptype','fellowship')->orderBy('groupname')->where('publish',1)->get();
        return view('church::' . $mode . '.groups',$data);
    }

    public function mymenu($mode="website"){
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
        return view('church::' . $mode . '.mymenu',$data);
    }

    public function project($id,$mode="website"){
        $data['project']=Project::find($id);
        return view('church::' . $mode . '.project',$data);
    }

    public function projects($mode="website"){
        $data['projects']=Project::orderBy('project')->paginate(10);
        return view('church::' . $mode . '.projects',$data);
    }

    public function series($year,$slug,$mode="website"){
        $data['series']=Series::with('sermons.person')->where('slug',$slug)->first();
        return view('church::' . $mode . '.series',$data);
    }
    
    public function sermons($mode="website") {
        $data['series']=Series::with('sermons')->orderBy('startingdate','DESC')->paginate(10);
        return view('church::' . $mode . '.sermons',$data);
    }

    public function sermon($year,$slug, $id,$mode="website"){
        $data['sermon']=Sermon::with('person')->where('id',$id)->first();
        $data['series']=Series::with('sermons')->where('id',$data['sermon']->series_id)->first();
        return view('church::' . $mode . '.sermon',$data);
    }

    public function person($slug,$mode="website"){
        $data['person']=Person::with('sermons','posts')->where('slug',$slug)->first();
        return view('church::' . $mode . '.person',$data);
    }
}
