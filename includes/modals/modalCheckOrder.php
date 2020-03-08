<div class="modal fade" tabindex="-1" id="modalCheckOrder" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Order now!</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to order these products?</p>
            </div>
            <div class="modal-footer">
                <form action="php/construct_order.php" method="post" id="theform">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    <input type="hidden" id="ordersCode" name="ordersCode">
                    <input type="hidden" id="ordersCount" name="ordersCount">
                    <button type="submit" class="btn btn-success" id="btnPlaceOrder">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>