[{block name="unzer_cardjs"}]
    [{oxscript include="https://static.unzer.com/v1/unzer.js"}]
[{/block}]
[{block name="unzer_card_css"}]
    [{oxstyle include="https://static.unzer.com/v1/unzer.css"}]
[{/block}]

<form id="payment-form" class="unzerUI form" novalidate>
    <div id="example-ideal" class="field"></div>
    <div class="field" id="error-holder" style="color: #9f3a38"> </div>
    <button class="unzerUI primary button fluid" id="submit-button" type="submit">[{oxmultilang ident="PAY"}]</button>
</form>


[{capture assign="unzerIDealJS"}]
        // Create an Unzer instance with your public key
        let unzerInstance = new unzer('[{$unzerpub}]');

        // Create an iDeal instance and render the iDeal form
        let IDeal = unzerInstance.Ideal();
        IDeal.create('ideal', {
            containerId: 'example-ideal'
        });

        // Handling payment form submission
        let form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            // Creating a IDeal resource
            IDeal.createResource()
                .then(function(result) {
                    let hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'resourceId');
                    hiddenInput.setAttribute('value', result.id);
                    form.appendChild(hiddenInput);
                    form.setAttribute('method', 'POST');
                    form.setAttribute('action', "sdf");

                    // Submitting the form
                    form.submit();
                })
                .catch(function(error) {
                    $('#error-holder').html(error.message)
                })
        });
[{/capture}]
[{oxscript add=$unzerIDealJS}]
