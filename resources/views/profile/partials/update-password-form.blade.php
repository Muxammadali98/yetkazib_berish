<section>
    @if (session('status') === 'password-updated')
        <div class="alert alert-success">
            Siz yuborgan ma'lumotlar muvaffaqiyatli saqlandi
        </div>
    @endif
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Mavjud parolingiz') }}
                    </label>
                    <input id="current_password" type="password" name="current_password" required
                           autocomplete="current-password" autofocus
                           class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                    <div class="invalid-feedback">
                        Notog'ri parol
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Yangi parolingiz') }}
                    </label>
                    <input id="password" type="password" name="password" required
                           autocomplete="new-password" autofocus
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <div class="invalid-feedback">
                        Yangi parol to'ldirilishi va 8ta belgidan kam bo'lmasligi kerak
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Yangi parolingizni takrorlang') }}
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           autocomplete="new-password" autofocus
                           class="form-control @error('password_confirmation') is-invalid @enderror">
                    @error('password_confirmation')
                    <div class="invalid-feedback">
                        Yangi parolni takrorlang
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="btn btn-success">{{ __('Saqlash') }}</x-primary-button>
        </div>
    </form>
</section>
