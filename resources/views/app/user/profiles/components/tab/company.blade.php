<table class="table borderless">
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.name') }}</span>
      {{ optional($company)->name }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.reg_number') }}</span>
      {{ optional($company)->reg_number }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.description') }}</span>
      {{ optional($company)->business_description }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.business_type') }}</span>
      {{ optional($company)->business_type }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.address') }}</span>
      {{ optional($company)->address }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.default_currency') }}</span>
      {{ optional($company)->default_currency }}
    </td>
  </tr>
</table>
