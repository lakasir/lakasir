<x-form :route="$route" :title="$title" :method="$method ?? null">
  <a href="{{ route('category.create') }}" class="float-right" target="_blank"> {{ __('app.categories.create.title') }}</a>
  <select2
     default-value="{{ optional(optional( $data ?? '' )->category ?? '')->id }}"
     label="{{ __('app.items.column.category.name') }}"
     placeholder="{{ __('app.items.placeholder.category.name') }}"
     name="category_id"
     url="{{ route('category.index') }}"
     keytext="id"
     text="name"
     old="{{ json_encode(old('category_id')) }}"
     @error('category_id')
     error-message="{{ $message }}"
     :error="true"
     @enderror
     >
  </select2>
  <a href="{{ route('unit.create') }}" class="float-right" target="_blank"> {{ __('app.units.create.title') }}</a>
  <select2
     default-value="{{ optional(optional( $data ?? '' )->unit ?? '')->id }}"
     label="{{ __('app.items.column.unit.name') }}"
     placeholder="{{ __('app.items.placeholder.unit.name') }}"
     name="unit_id"
     keytext="id"
     text="name"
     url="{{ route('unit.index') }}"
     old="{{ json_encode(old('unit_id')) }}"
     @error('unit_id')
     error-message="{{ $message }}"
     :error="true"
     @enderror
     >
  </select2>
  <div class="form-group">
    <label for="exampleFormControlFile1" class="text-muted"> {{ __('app.items.column.images') }}</label>
    <input type="file" name="image" class="form-control-file @error('image') 'is-invalid' @enderror" id="exampleFormControlFile1">
    <div>
      <img width="200" class="border p-3 my-3" src="{{ isset($data) ? media($data->media->first()) : ''}}" alt=""/>
    </div>
    @error('image')
    <div class="text-red">
      {{ $message }}
    </div>
    @enderror
  </div>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.items.placeholder.name') }}"
           label="{{ __('app.items.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
  @if (!isset($method))
  <hr>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.items.placeholder.stock.stock') }}"
           label="{{ __('app.items.column.stock.stock') }}"
           type="number"
           old="{{ old('stock') }}"
           @error('stock')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="stock"
           :validation="['required', 'numeric', 'min:1']"
           default-value="{{ optional( $data ?? '' )->stock }}"
           ></v-input>
    <hr>
    <v-input icon="fa-signature"
             placeholder="{{ __('app.items.placeholder.price.initial_price') }}"
             label="{{ __('app.items.column.price.initial_price') }}"
             old="{{ old('price.initial_price') }}"
             type="number"
             @error('initial_price')
             error-message="{{ $message }}"
             :error="true"
           @enderror
             name="initial_price"
             :validation="['required', 'numeric', 'min:1']"
             default-value="{{ optional( $data ?? '' )->initial_price }}"
             ></v-input>
    <v-input icon="fa-signature"
             placeholder="{{ __('app.items.placeholder.price.selling_price') }}"
             label="{{ __('app.items.column.price.selling_price') }}"
             old="{{ old('price.selling_price') }}"
             type="number"
             @error('selling_price')
             error-message="{{ $message }}"
             :error="true"
           @enderror
             name="selling_price"
             :validation="['required', 'numeric', 'min:1']"
             default-value="{{ optional( $data ?? '' )->selling_price }}"
             ></v-input>
    <hr>
  @endif
  <v-checkbox icon="fa-lock"
              placeholder="{{ __('app.items.placeholder.internal_production') }}"
              old="{{ old('internal_production') }}"
              name="internal_production"
              label="{{ __('app.items.column.internal_production') }}"
              @if (optional( $data ?? '' )->internal_production == true)
                :checked="true"
              @endif
              default-value="1"
              ></v-checkbox>
</x-form>
