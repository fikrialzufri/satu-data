@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <form id="passwordForm" action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="username" class=" form-control-label">Username</label>
                                </div>
                                <div>
                                    <input type="text" name="username" placeholder="Username"
                                        class="form-control  {{ $errors->has('username') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->username }}" disabled>
                                </div>
                                @if ($errors->has('username'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('username') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="Name" class=" form-control-label">Name</label>
                                </div>
                                <div>
                                    <input type="text" name="name" placeholder="Name User"
                                        class="form-control  {{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->name }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('name') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="email" class=" form-control-label">Email</label>
                                </div>
                                <div>
                                    <input type="text" name="email" placeholder="email"
                                        class="form-control  {{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->email }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('email') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <div>
                                    <label for="passwordNew" class=" form-control-label">Password Baru</label>
                                </div>
                                <div>
                                    <input id="passwordNew" type="password" name="passwordNew" placeholder="Password Baru"
                                        autocomplete="new-password" minlength="8"
                                        class="form-control  {{ $errors->has('passwordNew') ? 'form-control is-invalid' : 'form-control' }}">
                                    <ul class="password-requirements" aria-live="polite">
                                        <li data-password-rule="length"><i class="fas fa-circle"></i>Minimal 8 karakter</li>
                                        <li data-password-rule="lowercase"><i class="fas fa-circle"></i>Mengandung huruf kecil</li>
                                        <li data-password-rule="uppercase"><i class="fas fa-circle"></i>Mengandung huruf besar</li>
                                        <li data-password-rule="number"><i class="fas fa-circle"></i>Mengandung angka</li>
                                        <li data-password-rule="special"><i class="fas fa-circle"></i>Mengandung karakter khusus</li>
                                    </ul>
                                </div>
                                @if ($errors->has('passwordNew'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('passwordNew') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="passwordConfrim" class=" form-control-label">Konfirmasi Password</label>
                                </div>
                                <div>
                                    <input id="passwordConfrim" type="password" name="passwordConfrim" placeholder="Konfirmasi Password"
                                        autocomplete="new-password"
                                        class="form-control  {{ $errors->has('passwordConfrim') ? 'form-control is-invalid' : 'form-control' }}">
                                    <small id="passwordMatch" class="password-match" aria-live="polite">
                                        <i class="fas fa-circle"></i>Konfirmasi password harus sama
                                    </small>
                                </div>
                                @if ($errors->has('passwordConfrim'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('passwordConfrim') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->

@stop

@push('head')
    <style>
        .password-requirements {
            list-style: none;
            margin: .5rem 0 0;
            padding: 0;
        }

        .password-requirements li,
        .password-match {
            color: #6c757d;
            font-size: .875rem;
            margin-bottom: .25rem;
        }

        .password-requirements li i,
        .password-match i {
            margin-right: .35rem;
            width: 1rem;
            text-align: center;
        }

        .password-requirements li.valid,
        .password-match.valid {
            color: #28a745;
        }

        .password-requirements li.invalid,
        .password-match.invalid {
            color: #dc3545;
        }
    </style>
@endpush

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        $(function() {
            const $password = $('#passwordNew');
            const $confirmation = $('#passwordConfrim');

            const passwordRules = {
                length: value => value.length >= 8,
                lowercase: value => /[a-z]/.test(value),
                uppercase: value => /[A-Z]/.test(value),
                number: value => /[0-9]/.test(value),
                special: value => /[^A-Za-z0-9]/.test(value)
            };

            function updateRule(rule, isValid, hasValue) {
                const $item = $('[data-password-rule="' + rule + '"]');
                const $icon = $item.find('i');

                $item.removeClass('valid invalid');
                $icon.removeClass('fa-check fa-times fa-circle');

                if (isValid) {
                    $item.addClass('valid');
                    $icon.addClass('fa-check');
                } else if (hasValue) {
                    $item.addClass('invalid');
                    $icon.addClass('fa-times');
                } else {
                    $icon.addClass('fa-circle');
                }
            }

            function updatePasswordChecklist() {
                const value = $password.val();
                const hasValue = value.length > 0;

                Object.keys(passwordRules).forEach(function(rule) {
                    updateRule(rule, passwordRules[rule](value), hasValue);
                });

                const confirmation = $confirmation.val();
                const $match = $('#passwordMatch');
                const $matchIcon = $match.find('i');
                const hasConfirmation = confirmation.length > 0;
                const matches = hasValue && hasConfirmation && value === confirmation;

                $match.removeClass('valid invalid');
                $matchIcon.removeClass('fa-check fa-times fa-circle');

                if (matches) {
                    $match.addClass('valid');
                    $matchIcon.addClass('fa-check');
                } else if (hasConfirmation) {
                    $match.addClass('invalid');
                    $matchIcon.addClass('fa-times');
                } else {
                    $matchIcon.addClass('fa-circle');
                }
            }

            $password.add($confirmation).on('input', updatePasswordChecklist);

            $('#passwordForm').on('submit', function(event) {
                const value = $password.val();
                const confirmation = $confirmation.val();
                const changingPassword = value.length > 0 || confirmation.length > 0;
                const isStrong = Object.keys(passwordRules).every(function(rule) {
                    return passwordRules[rule](value);
                });

                if (changingPassword && (!isStrong || value !== confirmation)) {
                    event.preventDefault();
                    $password.trigger('focus');
                }
            });

            updatePasswordChecklist();
        });
    </script>
@endpush
