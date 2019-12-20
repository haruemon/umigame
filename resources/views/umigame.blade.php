@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">問題</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('answer') }}">
                            @csrf
                            <p>ある男が、とある海の見えるレストランで「ウミガメのスープ」を注文しました。<br>
                                しかし、彼はその「ウミガメのスープ」を一口飲んだところで止め、シェフを呼びました。<br>
                                「すみません。これは本当にウミガメのスープですか？」<br>
                                「はい･･･　ウミガメのスープに間違いございません。」<br>
                                男は勘定を済ませ、帰宅した後、自殺をしました。<br>
                                何故でしょう？</p>
                            <br>
                            <p>{{$resultText}}</p>

                            <div class="form-group row">
                                <label for="email" class="col-md-12 col-form-label">質問</label>

                                <div class="col-md-10">
                                    <input id="email" type="text" class="form-control @error('text') is-invalid @enderror" name="text" value="{{ old('text') }}" required autofocus>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        質問
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <a href="{{route('result')}}" class="btn btn-primary">わかった！！</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
