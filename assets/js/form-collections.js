const $ = require('jquery');
// setup an "add a tag" link

var $addProductLink = $('#add_new_product');
var $newLinkLi = $('<li class="list-group-item bg-transparent"></li>').append($addProductLink);

$(document).ready(
    createForm()
);

function createForm() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('ul.products');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    createProductApi($collectionHolder, $newLinkLi);
    $addProductLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addProductForm($collectionHolder, $newLinkLi);
    });
}

function addProductForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    // increase the index with one for the next item
    
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="list-group-item bg-transparent new-product-form"></li>').prepend(newForm);

    // also add a remove button, just for this example
    $newFormLi.prepend('<a href="#" class="remove-product">x</a>');

    $newLinkLi.after($newFormLi);

    // handle the removal, just for this example
    $('.remove-product').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });

    grossToNet();
    netToGross();
    onVatChange();
}

$('.remove-product').click(function(e) {
    e.preventDefault();

    $(this).parent().remove();

    return false;
});

function createProductApi($collectionHolder, $newLinkLi)
{
    let select = $('#invoice_existProduct');

    select.on('change', function () {
        let selectedProduct = $(this).children("option:selected").val();
        let apiProductUrl = select.closest('li').data('api-product');
        $('#loadInvoiceProduct').show();
        if (selectedProduct > 0)
        {
            let apiUrlProductPath = apiProductUrl + '/' + selectedProduct;
            $.ajax({
                method: 'POST',
                url: apiUrlProductPath,
            }).done(function (data) {
                $('#loadInvoiceProduct').hide();
                addProductForm($collectionHolder, $newLinkLi);
                let liForm = $('ul li.new-product-form')[0]
                let product = JSON.parse(data);
                $(liForm).find("input[name*='name']" ).val(product['name']);
                $(liForm).find("input[name*='grossValue']" ).val(product['grossValue']);
                $(liForm).find("input[name*='netValue']" ).val(product['netValue']);
                $(liForm).find("input[name*='quantity']" ).val(product['quantity']);
                $(liForm).find("select[name*='vat'] option[value=" + product['vat'] + ']').attr('selected', 'selected');
                $(liForm).find("input[name*='forPeriod']" ).val(product['forPeriod']);
                $(liForm).find("input[name*='id']" ).val(product['id']); 
                onVatChange()
            })
        }
    })
}

grossToNet();
netToGross();
onVatChange();

function grossToNet() 
{
     $("input[name*='grossValue']").on('change keypress', function(event) {
        let grossValue = $(this).val();
        let vat = $(this).closest('li').find("select[name*='vat']").children('option:selected').val();
        
        let netValue = grossValue /(1 + vat/100);
        netValue = netValue.toFixed(2);
        
        $(this).closest('li').find("input[name*='netValue']").val(netValue);
    });
}

function onVatChange()
{
    $("select[name*='vat']").on('change', function($event) {
        let newVat = $($event.currentTarget).children('option:selected').val();
        let netValue = $($event.currentTarget).closest('li').find("input[name*='netValue']").val();
        let grossValue = netValue * (1 + newVat/100);
        grossValue = grossValue.toFixed(2);
        $($event.currentTarget).closest('li').find("input[name*='grossValue']").val(grossValue)
    })
}

function netToGross() 
{
     $("input[name*='netValue']").on('change keypress', function(event) {
        let netValue = $(this).val();
        let vat = $(this).closest('li').find("select[name*='vat']").children('option:selected').val();
        let grossValue = netValue * (1 + vat/100);
        grossValue = grossValue.toFixed(2);
        
        $(this).closest('li').find("input[name*='grossValue']").val(grossValue);
    });
}


$(document).ready(function(){
    $("#myModal").modal('show');
});

$("input[name*='accountNumber']").on('keypress', function(event) {
    let value = $(this).val();
    accountSpacesAfter = [2, 7, 12, 17, 22, 27];
    if (accountSpacesAfter.includes(value.length)) {
        $(this).val(value + ' ');
    }
});

$(document).ready(function(){
    $(".searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});