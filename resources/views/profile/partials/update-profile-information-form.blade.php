<section>
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">
            Siz yuborgan ma'lumotlar muvaffaqiyatli saqlandi
        </div>
    @endif
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Ismingiz') }}
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                           autocomplete="name" autofocus
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">
                        Ismingiz to'ldirilishi shart
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Telefon raqamingiz') }}
                    </label>
                    <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                           autocomplete="phone_number" autofocus
                           class="form-control @error('phone_number') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">
                        Telefon raqamingiz to'ldirilishi shart
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{ __('Email') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                           autocomplete="email" autofocus
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <div class="invalid-feedback">
                        Email to'ldirilishi va email bo'lishi shart
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
