<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('title')
    @yield('description')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

@include('include.header')

<main class="container">
    <div class="row">
        <article class="col-md-12">
            @include('include.breadcrumbs')
            @yield('content')
        </article>
    </div>
</main>

@include('include.footer')

<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('order-service.send') }}" method="POST">
        @csrf
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('modal.callback-title') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="feedback_service" class="form-label">{{ __('modal.service-title') }}</label>
                <select class="form-select" name="feedback_service" aria-label="Default select example">
                    <option selected>{{ __('modal.service-select') }}</option>
                    <option value="Develop">Develop</option>
                    <option value="SEO Optimization">SEO Optimization</option>
                    <option value="Design">Design</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="feedback_email" class="form-label">{{ __('modal.email-title') }}</label>
                <input type="email" class="form-control" name="feedback_email" placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label for="feedback_message" class="form-label">{{ __('modal.message-title') }}</label>
                <textarea class="form-control" name="feedback_message" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">{{ __('modal.send-button') }}</button>
        </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="feedbackOrderModal" tabindex="-1" aria-labelledby="feedbackOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order-service.send') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('modal.callback-title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="feedback_service" class="form-label">{{ __('modal.service-title') }}</label>
                        <select class="form-select" name="feedback_service" aria-label="Default select example">
                            <option selected>{{ __('modal.service-select') }}</option>
                            <option value="Develop">Develop</option>
                            <option value="SEO Optimization">SEO Optimization</option>
                            <option value="Design">Design</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="feedback_email" class="form-label">{{ __('modal.email-title') }}</label>
                        <input type="email" class="form-control" name="feedback_email" placeholder="name@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="feedback_message" class="form-label">{{ __('modal.message-title') }}</label>
                        <textarea class="form-control" name="feedback_message" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('modal.send-button') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
