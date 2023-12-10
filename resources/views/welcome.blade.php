@extends('frontend.layout.'.getSetting()->theme .'.master')
@section('title')
    {{trans('app.my_exams')}}
@endsection
@section('seo')

@endsection
@section('css')

@endsection
@section('content')
    <!-- Start Page Title Area -->
    <div class="page-title-area page-title-style-two item-bg2 jarallax">
        <div class="container">
            <div class="page-title-content">
                <ul>
                    <li><a href="{{ route('index') }}">{{trans('app.home')}}</a></li>
                    <li>{{trans('app.my_exams')}}</li>
                </ul>
                <h2>{{trans('app.my_exams')}}</h2>
            </div>
        </div>
    </div>
    <!-- End Page Title Area -->

    <!-- Start My Account Area -->
    <section class="my-account-area ptb-100">
        <div class="container">
            <div class="myAccount-profile">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-5">
                        <div class="profile-image">
                            @if(Auth::guard('student')->user()->image)
                                <img src="{{asset('upload/student/'.Auth::guard('student')->user()->image)}}" alt="image">
                            @else
                                <img src="{{asset('frontend/theme1/assets/img/team/1.jpg')}}" alt="image">
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="profile-content">
                            <h3>{{Auth::guard('student')->user()->first}} {{Auth::guard('student')->user()->last}}</h3>

                            <ul class="contact-info">
                                <li><i class='bx bx-envelope'></i> <a href="mailto:{{Auth::guard('student')->user()->email}}">{{Auth::guard('student')->user()->email}}</a></li>
                            </ul>
                            <a href="{{ route('student_logout') }}" class="myAccount-logout">{{trans('app.logout')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="myAccount-navigation">
                @include('frontend.theme1.student.menu')
            </div>

            <div class="myAccount-content">
                <p>{{trans('app.welcome')}} <strong>{{Auth::guard('student')->user()->first}}</strong> ({{trans('app.not')}} <strong>{{Auth::guard('student')->user()->first}}</strong> ? <a href="{{ route('student_logout') }}">{{trans('app.logout')}}</a>)</p>
                <p>{{trans('app.notes1')}} <a href="{{ route('student_dashboard') }}">{{trans('app.your_order')}}</a>,{{trans('app.notes2')}} <a href="#">{{trans('app.notes3')}}</a>,
                    {{trans('app.and')}} <a href="#">{{trans('app.notes4')}}</a>.</p>
                <h3>{{trans('app.my_exams')}}</h3>
                <div class="recent-orders-table table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('app.exam_name')}}</th>
                            <th>{{trans('app.course_name')}}</th>
                            <th>{{trans('app.exam_type')}}</th>
                            <th>{{trans('app.exam_grade')}}</th>
                            <th>{{trans('app.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\StudentCourse::where('student_id', Auth::guard('student')->user()->id)->get() as $course)
                            @if(\App\Models\Course::where('id',$course->id)->where('quizzes',0)->first())
                                @foreach(\App\Models\Section::where('course_id', $course->course_id)->get() as $section)
                                    @php
                                        $syudent_lessons    =   \App\Models\StudentLesson::where('student_id', Auth::guard('student')->user()->id)->where('section_id', $section->id)->count();

                                        $lessons            =   \App\Models\Lesson::where('section_id', $section->id)->get() ->count();
                                    @endphp
                                @endforeach
                                @if($syudent_lessons == $lessons)
                                    @foreach(\App\Models\Exam::where('course_id', $course->course_id)->get() as $exam)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$exam->name}}</td>
                                            <td>{{$exam->course->name}}</td>
                                            <td>
                                                @if($exam->type == 0)
                                                    Training Exam
                                                @else
                                                    Research Exam
                                                @endif
                                            </td>
                                            <td>
                                                @if($exam->type == 0)
                                                    {{\App\Models\StudentExam::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->sum('grade')}} / {{$exam->point * $exam->questions->count()}}
                                                @else
                                                    @if(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 0)
                                                        {{\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->grade}} / {{$exam->point}}
                                                    @elseif(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 1)
                                                        {{\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->grade}} / {{$exam->point}}
                                                    @elseif(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 2)
                                                        <a class="btn btn-danger text-white">Refused</a>
                                                    @elseif(!\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first())
                                                        {{'0'}} / {{$exam->point}}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if(\App\Models\StudentExam::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->count() > 0)
                                                    <a  class="btn btn-success">Completed</a>
                                                @else
                                                    @if($exam->type == 0)
                                                        <a href="{{ route('student_exam_questions', encrypt($exam->id)) }}" class="default-btn">Get Exam</a>
                                                    @else
                                                        @if(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 2)
                                                            <!-- Button trigger modal Upload Research-->
                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal{{$exam->id}}">
                                                                <i class="fa fa-upload"></i>ReUpload Research
                                                            </button>
                                                        @elseif(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 1)
                                                            <a class="btn btn-success text-white">Succeed</a>
                                                        @elseif(\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first() && \App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first()->status == 0)
                                                            <a class="btn btn-warning text-white">Pending Review</a>
                                                        @elseif(!\App\Models\ExamResearch::where('exam_id', $exam->id)->where('student_id', Auth::guard('student')->user()->id)->first())
                                                            <!-- Button trigger modal Upload Research-->
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$exam->id}}">
                                                                <i class="fa fa-upload"></i>Upload Research
                                                            </button>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Modal Upload Research -->
                                        <div class="modal fade" id="exampleModal{{$exam->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            {{$exam->name}}</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('student_store_research_exam') }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">Browse Research File</label>
                                                                    <input type="file" class="form-control" name="attachment" required>
                                                                    <input type="hidden" name="exam_id" value="{{$exam->id}}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success">Upload Research</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
    <!-- End My Account Area -->
@endsection
@section('js')

@endsection
