@extends('layouts.vertical', ['title' => 'Videos', 'topbarTitle' => 'Videos'])

@section('content')

    <div class="container">
        <div class="card p-4">
        <h2 class="mb-4 text-center">Tutorial Videos</h2>
        <div class="row">
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/YCcQujkOkMw?si=Bx0wy-EZJh-Rv5-A" frameborder="0"
                    allowfullscreen></iframe>
            </div>
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/dl8k9M-0P58?si=sJsKKWNS1MHspZI9" frameborder="0"
                    allowfullscreen></iframe>
            </div>
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/YCcQujkOkMw?si=xZjLQqJQdePHHmDM" frameborder="0"
                    allowfullscreen></iframe>
            </div>
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/7IOjUkDTqv0?si=7_MWX0GXVjYuBoOf" frameborder="0"
                    allowfullscreen></iframe>
            </div>
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/SL7DcXh7TmA?si=_LZ771C00P66H7Bk" frameborder="0"
                    allowfullscreen></iframe>
            </div>
            <div class="col-md-6 mb-4 tutorial-video">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/Fj9bN3Khquk?si=TkN_wQPaA5hqx381" frameborder="0"
                    allowfullscreen></iframe>
            </div>
        </div>
        </div>
    </div>
@endsection
