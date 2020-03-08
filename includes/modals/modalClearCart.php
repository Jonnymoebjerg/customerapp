<div id="modalClearCart" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Clear Cart <small>- Start from scratch!</small></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to clear the cart?</p>
                <p>If you need to order new products, please visit our website or contact us and we will figure it out.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="clearCart()">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<script src="js/jsNotify.js"></script>
<script>
    function clearCart() {
        if (sessionStorage.getItem("cartItemIds") === "") {
            pushNotificationCartAlreadyEmpty();
        } else {
            sessionStorage.setItem("cartItemIds", "");
            sessionStorage.setItem("cartItemQuan", "");
            $( "#cartBody" ).empty();
            $("#cartBody").append("<h2>Cart empty!</h2>");
            $(".btnCartOrder").attr("disabled","disabled");
            $(".btnClearCart").attr("disabled","disabled");
            $( "<style>body { background-color: lightgray !important; }</style>" ).appendTo( "head" );
            pushNotificationCartEmpty();
            getCart();
        }
    }
</script>