<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\Printer as PrinterModel;
use App\Services\Tenants\PrinterService;
use App\Traits\HasTranslatableResource;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class Printer extends Page implements HasActions, HasForms
{
    use HasTranslatableResource;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static string $view = 'filament.tenant.pages.printer';

    public ?array $data = [];

    public function mount()
    {
        $printer = PrinterModel::first();

        if ($printer) {
            $this->form->fill([
                'name' => $printer->name,
                'driver' => $printer->driver,
                'ip_address' => $printer->ip_address,
                'port' => $printer->port,
                'logo' => $printer->logo,
                'footer_text' => $printer->footer_text,
            ]);
        } else {
            $this->form->fill([
                'driver' => 'api',
                'ip_address' => 'localhost',
                'port' => '5463',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\TextInput::make('name')
                ->required()
                ->default('Thermal Printer')
                ->helperText(__('Give your printer a name'))
                ->translateLabel(),
            Components\Select::make('driver')
                ->default('api')
                ->required()
                ->options([
                    'api' => 'API',
                    'usb' => 'USB',
                ])
                ->translateLabel(),
            Grid::make(columns: 2)
                ->schema([
                    Components\TextInput::make('ip_address')
                        ->label(__('IP Address / Host'))
                        ->default('localhost')
                        ->required()
                        ->helperText(__('e.g., localhost or 192.168.1.100'))
                        ->translateLabel(),
                    Components\TextInput::make('port')
                        ->label(__('Port'))
                        ->default('5463')
                        ->numeric()
                        ->helperText(__('Default: 5463'))
                        ->translateLabel(),
                ]),
            Components\Placeholder::make('info')
                ->label(__('Printer API URL'))
                ->content(fn ($get) => 'http://' . ($get('ip_address') ?: 'localhost') . ':' . ($get('port') ?: '5463') . '/print'),
            Components\Section::make(__('Receipt Customization'))
                ->schema([
                    Components\FileUpload::make('logo')
                        ->label(__('Logo'))
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '1:1',
                            '16:9',
                            '4:3',
                        ])
                        ->disk('public')
                        ->directory('printer-logos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText(__('Upload logo for receipt header (max 2MB)'))
                        ->nullable()
                        ->translateLabel(),
                    Components\Textarea::make('footer_text')
                        ->label(__('Footer Text'))
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText(__('Text to display at the bottom of receipts'))
                        ->placeholder(__('Thank you for your business!\nVisit us again soon!'))
                        ->translateLabel(),
                ]),
        ])->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->translateLabel()
                ->action('save'),
            Action::make('test')
                ->translateLabel()
                ->color('warning')
                ->icon('heroicon-o-printer')
                ->action('testPrinter'),
        ];
    }

    public function save()
    {
        $validatedData = $this->validate([
            'data.name' => 'required',
            'data.driver' => 'required',
            'data.ip_address' => 'required',
            'data.port' => 'nullable|numeric',
            'data.logo' => 'nullable',
            'data.footer_text' => 'nullable|string|max:500',
        ]);

        $data = $this->data;
        
        if (empty($data['logo'])) {
            $data['logo'] = null;
        }
        
        if (empty($data['footer_text'])) {
            $data['footer_text'] = null;
        }

        $printer = PrinterModel::first();

        if ($printer) {
            $printer->update($data);

            Notification::make()
                ->title(__('Printer updated successfully'))
                ->success()
                ->send();
        } else {
            PrinterModel::create($data);

            Notification::make()
                ->title(__('Printer created successfully'))
                ->success()
                ->send();
        }
    }

    public function testPrinter()
    {
        $this->validate([
            'data.name' => 'required',
            'data.ip_address' => 'required',
            'data.port' => 'nullable|numeric',
        ]);

        try {
            $tempPrinter = new PrinterModel($this->data);
            $printerService = new PrinterService($tempPrinter);

            $result = $printerService
                ->buildTestReceipt($this->data['name'])
                ->printWithCustomPrinter([
                    'ip_address' => $this->data['ip_address'],
                    'port' => $this->data['port'] ?? '5463',
                ]);

            $apiUrl = 'http://' . $this->data['ip_address'] . ':' . ($this->data['port'] ?? '5463');

            Notification::make()
                ->title(__('Test print successful'))
                ->body(__('Test receipt sent to printer at :url', ['url' => $apiUrl]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Test print failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function validateInput()
    {
        $this->validate([
            'data.name' => 'required',
            'data.ip_address' => 'required',
        ]);
    }
}
