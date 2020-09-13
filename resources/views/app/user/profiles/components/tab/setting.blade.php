<x-form :route="route('profile.store')" :title="__('app.profiles.create.title')" :method="$method ?? null" :card="false" size="12">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.profiles.placeholder.phone') }}"
           label="{{ __('app.profiles.column.phone') }}"
           old="{{ old('phone') }}"
           @error('phone')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="phone"
           :validation="['required']"
           default-value="{{ optional( auth()->user()->profile ?? '' )->phone }}"
           ></v-input>

  <v-input icon="fa-signature"
           placeholder="{{ __('app.profiles.placeholder.address') }}"
           label="{{ __('app.profiles.column.address') }}"
           old="{{ old('address') }}"
           @error('address')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="address"
           :validation="['required']"
           default-value="{{ optional( auth()->user()->profile ?? '' )->address }}"
           ></v-input>
  <v-text-area icon="fa-building"
           placeholder="{{ __('app.profiles.placeholder.bio') }}"
           default-value="{{ optional( auth()->user()->profile ?? '' )->bio }}"
           label="{{ __('app.profiles.column.bio') }}"
           name="bio"
           old="{{ old('bio') }}"
           @error('bio')
           :error="true"
           error-message="{{ $message }}"
           @enderror
  ></v-text-area>
  <div class="form-group">
    <label for="exampleFormControlFile1" class="text-muted"> {{ __('app.profiles.column.photo_profile') }}</label>
    <input type="file" name="photo_profile" class="form-control-file @error('photo_profile') 'is-invalid' @enderror" id="exampleFormControlFile1">
    @error('photo_profile')
    <div class="text-red">
      {{ $message }}
    </div>
    @enderror
  </div>
  <v-select icon="fa-building"
           placeholder="{{ __('app.profiles.placeholder.lang') }}"
           options="{{ collect([['id' => 'en', 'text' => 'English USA'],['id' => 'id', 'text' => 'Bahasa Indonesia'],]) }}"
           default-value="{{optional(optional(auth()->user() ?? '')->profile)->lang}}"
           label="{{ __('app.profiles.column.lang') }}"
           name="lang"
           old="{{ old('lang') }}"
           @error('lang')
           :error="true"
           error-message="{{ $message }}"
           @enderror
    ></v-select>
</x-form>
