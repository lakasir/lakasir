<?php

namespace App\Livewire\Receivables;

use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivablePayment;
use App\Services\Tenants\ReceivablePaymentService;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Receivable $receivable;
    
    public $amount;
    public $date;
    public $payment_method_id;
    
    public $paymentMethods = [];

    public function mount(Receivable $receivable)
    {
        $this->receivable = $receivable->load(['member', 'selling.sellingItems.product', 'receivablePayments.paymentMethod']);
        
        $this->date = now()->format('Y-m-d');
        $this->paymentMethods = PaymentMethod::where('is_credit', false)->get();
    }

    public function processPayment()
    {
        if ($this->receivable->status) {
            session()->flash('error', 'Piutang ini sudah lunas.');
            return;
        }

        $this->validate([
            'amount' => 'required|numeric|min:1|max:' . $this->receivable->rest_receivable,
            'date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $service = new ReceivablePaymentService();
        
        try {
            // override Auth for the service since it uses Filament::auth()
            Auth::shouldUse('web');
            
            $service->create($this->receivable, [
                'amount' => $this->amount,
                'date' => $this->date,
                'payment_method_id' => $this->payment_method_id,
            ]);
            
            $this->receivable->refresh();
            $this->resetPaymentForm();
            $this->dispatch('close-modal', 'payment-modal');
            session()->flash('message', 'Pembayaran berhasil dicatat.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function deletePayment($id)
    {
        $payment = ReceivablePayment::findOrFail($id);
        
        if ($payment->receivable_id == $this->receivable->id) {
            $service = new ReceivablePaymentService();
            $service->destroy($payment);
            
            $this->receivable->refresh();
            session()->flash('message', 'Pembayaran berhasil dihapus. Sisa piutang telah disesuaikan.');
        }
    }

    public function resetPaymentForm()
    {
        $this->amount = null;
        $this->date = now()->format('Y-m-d');
        $this->payment_method_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.receivables.show')->title('Detail Piutang');
    }
}
