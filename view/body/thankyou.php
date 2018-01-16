<div class="container">
	<div class="row">
        <div class="col-sm-10 col-lg-6 mx-auto pt-3">
            <h1 class="py-md-3">感謝您的支持！</h1>
            <div class="card mb-3">
                <img class="card-img-top" src="images/article/26804015_138111763527272_80447334_n.jpg" alt="Card image cap">
                <div class="card-body">
                    <a href="#" class="btn btn-primary btn-block">下載</a>
                </div>
            </div>
            <?php if(count($productKeys) > 1) { ?>
            <div class="card mb-3">
                <img class="card-img-top" src="images/article/26853157_138111770193938_1490175646_o.jpg" alt="Card image cap">
                <div class="card-body">
                    <a href="#" class="btn btn-primary btn-block">下載</a>
                </div>
            </div>
            <?php }?>
            <a href="<?=Config::BASE_URL?>do_unset">回首頁</a>
        </div>
    </div>
</div>