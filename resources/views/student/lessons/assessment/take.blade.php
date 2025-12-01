<x-home-fullscreen-master>

    @section('page-title') {{ ucfirst($assessment->type) }} Test — {{ $lesson->title }} | {{$settings->app_name}}@endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection


    @section('content')
    <div class="card mt-5 mb-3">
        <div class="card-header">
            <h4 class="m-0">{{ strtoupper($assessment->type) }} Test — {{ $lesson->title }}</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="{{ route('assessments.submit',$assessment) }}">
                @csrf
                <input type="hidden" name="started_at" value="{{ now() }}">

                @forelse ($questions as $i => $q)
                    <div class="mb-4 border rounded p-3">
                        <div class="mb-2"><strong>Q{{ $i+1 }}.</strong> {{ $q->question }}</div>
                        @php $opts = $q->options(); @endphp
                        @foreach ($opts as $num => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="q[{{ $q->id }}]" id="q{{ $q->id }}_{{ $num }}" value="{{ $num }}" {{ $loop->first ? 'required' : '' }}>
                                <label class="form-check-label" for="q{{ $q->id }}_{{ $num }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p>No questions have been added to this lesson’s test.</p>
                @endforelse

                <button class="btn btn-primary" {{ $questions->count() ? '' : 'disabled' }}>Submit</button>
                <a href="{{ route('lessons.start', $lesson) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
    @endsection



</x-home-fullscreen-index>
