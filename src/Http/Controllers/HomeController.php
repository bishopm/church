<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Models\Attendance;
use Bishopm\Church\Models\Book;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
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

    public function blogpost($yr,$mth,$slug){
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
        return view('church::website.blogpost',$data);
    }

    public function blog() {
        $data['posts']=Post::orderBy('published_at','DESC')->paginate(10);
        return view('church::website.blog',$data);
    }

    public function blogger($slug) {
        $blogger=Person::where('slug',$slug)->first();
        $data['posts']=Post::where('person_id',$blogger->id)->orderBy('published_at','DESC')->paginate(10);
        return view('church::website.blogger',$data);
    }

    public function subject($slug){
        $data['tag']=Tag::findFromString($slug);
        $data['posts']=Post::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['sermons']=Sermon::withAnyTags($data['tag']->name)->where('published',1)->get();
        $data['books']=Book::withAnyTags($data['tag']->name)->get();
        return view('church::website.tag',$data);
    }

    public function book($id){
        $data['book']=Book::find($id);
        return view('church::website.book',$data);
    }

    public function books(){
        $data['books']=Book::orderBy('title')->paginate(10);
        return view('church::website.books',$data);
    }

    public function giving(){
        return view('church::website.giving');
    }

    public function mymenu(){
        $data=array();
        $data['indiv']=Individual::find($this->member['id']);
        $data['servicegroups']=Group::where('grouptype','service')->whereHas('individuals', function ($q) {
            $q->where('individuals.id',$this->member['id']); })->get();
        $data['fellowship']=Group::where('grouptype','fellowship')->whereHas('individuals', function ($q) {
            $q->where('individuals.id',$this->member['id']); })->get();
        $lastyear=date('Y-m-d',strtotime('-1 year'));
        $data['giving']=Gift::where('pgnumber',$data['indiv']->giving)->where('paymentdate','>=',$lastyear)->get();
        $worships=Attendance::where('individual_id',$this->member['id'])->where('attendancedate','>=',$lastyear)->get();
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
        foreach ($roster->rosteritems as $ri){
            if ($ri->rosterdate>$today){
                $data['roster'][$ri->rosterdate][]=$ri->rostergroup->group->groupname;
            }
        }
        ksort($data['roster']);
        return view('church::website.mymenu',$data);
    }

    public function project($id){
        $data['project']=Project::find($id);
        return view('church::website.project',$data);
    }

    public function projects(){
        $data['projects']=Project::orderBy('project')->paginate(10);
        return view('church::website.projects',$data);
    }

    public function series($year,$slug){
        $data['series']=Series::with('sermons.person')->where('slug',$slug)->first();
        return view('church::website.series',$data);
    }
    
    public function sermons() {
        $data['series']=Series::with('sermons')->orderBy('startingdate','DESC')->paginate(10);
        return view('church::website.sermons',$data);
    }

    public function sermon($year,$slug, $id){
        $data['sermon']=Sermon::with('person')->where('id',$id)->first();
        $data['series']=Series::with('sermons')->where('id',$data['sermon']->series_id)->first();
        return view('church::website.sermon',$data);
    }

    public function person($slug){
        $data['person']=Person::with('sermons','posts')->where('slug',$slug)->first();
        return view('church::website.person',$data);
    }
}
