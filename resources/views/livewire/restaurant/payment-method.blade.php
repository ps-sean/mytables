<div>
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            Payment Method
        </x-slot>
        <x-slot name="description">
            Update your Payment Method.
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 space-y-5">
                <div>
                    <h3 class="font-bold">Current Payment Method</h3>
                    @if($restaurant->hasDefaultPaymentMethod())
                        <div class="border-gray-200 shadow rounded p-6 flex justify-between items-center">
                            @php($card = $restaurant->defaultPaymentMethod()->card)
                            <p>{{ ucwords($card->brand) }} ({{ $card->last4 }})</p>
                            <p>exp. {{ \Carbon\Carbon::parse($card->exp_year . "-" . $card->exp_month)->format("M Y") }}</p>
                        </div>
                    @else
                        <p>None</p>
                    @endif
                </div>
                <div wire:ignore class="space-y-3">
                    <h3 class="font-bold">Add New Payment Method</h3>
                    <div class="space-y-2">
                        <label>Cardholder Name</label>
                        <x-jet-input class="w-full" id="card-holder-name" type="text" placeholder="Cardholder Name"/>
                    </div>
                    <div class="space-y-2">
                        <label>Card Details</label>
                        <div class="py-3 rounded-none border-0 border-b focus:ring-0 focus:border-red-800 focus:border-b-2" id="card-element"></div>
                        <div class="text-red-600" id="payment-error"></div>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button type="button" id="save-card" data-secret="{{ $restaurant->createSetupIntent()->client_secret }}" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>

    @push("scripts")
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener("livewire:load", () => {
                const stripe = Stripe('{{ config("services.stripe.public") }}')

                const elements = stripe.elements()
                const cardElement = elements.create('card')

                cardElement.mount('#card-element')

                const cardholderName = document.getElementById('card-holder-name')
                const saveButton = document.getElementById('save-card')
                const clientSecret = saveButton.dataset.secret

                saveButton.addEventListener("click", async (e) => {
                    e.preventDefault()

                    saveButton.disabled = true
                    document.getElementById('payment-error').innerText = ""

                    const { setupIntent, error } = await stripe.confirmCardSetup(
                        clientSecret, {
                            payment_method: {
                                card: cardElement,
                                billing_details: {
                                    name: cardholderName.value
                                }
                            }
                        }
                    )

                    if(error){
                        // display an error
                        document.getElementById('payment-error').innerText = error.message
                    } else {
                        @this.addCard(setupIntent.payment_method)
                    }

                    saveButton.disabled = false
                })
            })
        </script>
    @endpush
</div>
