<table class="table borderless">
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.name') }}</span>
      {{ optional($data->get('companies'))->name }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.reg_number') }}</span>
      {{ optional($data->get('companies'))->reg_number }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.description') }}</span>
      {{ optional($data->get('companies'))->business_description }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.type') }}</span>
      {{ optional($data->get('companies'))->business_type }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.address') }}</span>
      {{ optional($data->get('companies'))->address }}
    </td>
  </tr>
  <tr>
    <td>
      <span class="text-muted small d-block">{{ __('app.companies.column.default_currency') }}</span>
      {{ optional($data->get('companies'))->default_currency }}
    </td>
  </tr>
</table>
