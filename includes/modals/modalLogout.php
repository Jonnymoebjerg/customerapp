<div id="modalLogout" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Logout <small>- We are sorry to see you  go!</small></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer">
                <a href="php/logout.php"><button type="button" class="btn btn-success" onclick="clearStuff()">Yes</button></a>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<script>
    function clearStuff() {
        sessionStorage.setItem('cartItemIds','');
        sessionStorage.setItem('cartItemQuan','');
    }
</script>