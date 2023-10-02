@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/assets/bundles/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Foydalanuvchi qo'shish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('user.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('user.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <x-input-label for="name" :value="__('F.I.Sh')" />
                                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Parol')" />

                                    <x-text-input id="password" class="form-control"
                                                  type="password"
                                                  name="password"
                                                  required autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-4">
                                    <x-input-label for="password_confirmation" :value="__('Parolni tasdiqlang')" />

                                    <x-text-input id="password_confirmation" class="form-control"
                                                  type="password"
                                                  name="password_confirmation" required />

                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-4">
                                    <x-input-label for="roles" :value="__('Lavozimlar')" />
                                    <select name="roles[]" id="roles" class="form-control select2" multiple>
                                        @foreach($roles as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-4">
                                    <x-input-label for="phone_number" :value="__('Telefon raqam')" />
                                    <x-text-input id="phone_number" class="form-control" type="text" name="phone_number" :value="old('phone_number')" required autofocus />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Filiallar')" />
                                    <select name="region_id[]" id="region_id" class="form-control select2" multiple>
                                        @foreach($regions as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button class="btn btn-success">
                                        {{ __('Saqlash') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/bundles/select2/dist/js/select2.full.min.js"></script>
@endsection
