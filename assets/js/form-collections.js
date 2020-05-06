const $ = require('jquery');
// setup an "add a tag" link
var $addProductLink = $('<a href="#" class="add_tag_link badge badge-pill badge-inf">Add new</a>');
var $newLinkLi = $('<li class="list-group-item bg-transparent"></li>').append($addProductLink);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('ul.products');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addProductLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addProductForm($collectionHolder, $newLinkLi);
    });


});

function addProductForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    console.log($(prototype).find('.form-control'));
    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="list-group-item bg-transparent"></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-product">x</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-product').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

$('#invoice_existProduct').on('change', function () {
    let selectedProduct = $(this).children("option:selected").val();
    console.log(selectedProduct);
})