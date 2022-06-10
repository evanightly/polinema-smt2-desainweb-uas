<?php include_once "../template/header.php" ?>

<?php include "../components/client_dashboard_navbar.php" ?>

<section class="client-cart">
    <div class="container pt-5">
        <div class="row justify-content-between">
            <form class="col-md-4" action="" method="post">
                <div class="form-group mb-3">
                    <label class="form-label" for="username">Atas Nama:</label>
                    <input class="form-control" type="text" name="username" id="username" value="<?php echo $_SESSION['logged']['username'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="alamat">Alamat:</label>
                    <input class="form-control" type="text" name="alamat" id="alamat" value="<?php echo $_SESSION['logged']['alamat'] ?>">
                </div>
                <label for="">Sistem Pembayaran:</label>
            </form>
            <div class="col-md-6 items">
                <?php
                include_once "../config/connect.php";
                $id_customer = $_SESSION['logged']['id'];
                $query = "SELECT * FROM penjualan p JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan JOIN barang b ON b.id_barang = dp.id_barang WHERE id_customer = $id_customer";
                $data = $mysqli->query($query);
                $image = null;
                if ($data->num_rows > 0) {
                    $data->fetch_assoc();
                    foreach ($data as $key) {
                ?>
                        <div class="item d-flex">
                            <div class="row">
                                <div class="col-md-6 image"><img class="w-25 h-100" src="<?php $key['image'] ? print $key['image'] : print '../assets/static_images/dummy.png' ?>" alt="dummy"></div>
                                <div class="col-md-6 d-flex justify-content-around align-items-center">
                                    <h6 class="m-0"><?php echo $key['nama_barang'] ?></h6>
                                    <h6 class="m-0">x<?php echo $key['jumlah'] ?></h6>
                                    <h6 class="m-0">Rp.<?php echo $key['total'] ?></h6>
                                </div>
                            </div>
                            <a class="d-flex align-items-center" href="<?php echo $address ?>/client/cart.php?deleteItem=<?php echo $key['id_detail_penjualan'] ?>"><i class="bi bi-trash text-danger"></i></a>
                        </div>
                <?php
                    }
                }
                ?>
                <hr>
                <?php
                if (isset($_GET['deleteItem'])) {
                    $itemId = $_GET['deleteItem'];
                    $query = "DELETE FROM detail_penjualan WHERE id_detail_penjualan = $itemId";
                    $mysqli->query($query);
                    header("location: " . $address . "/client/cart.php");
                }

                $query = "SELECT SUM(dp.total) AS total FROM penjualan p JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan WHERE id_customer = $id_customer";
                $data = $mysqli->query($query);
                if ($data->num_rows > 0) {
                    $total = $data->fetch_assoc()['total'];
                    echo "<h5>Total: Rp. " . $total . " </h5>";
                    echo "<input type='hidden' name='total' value='$total'>";
                }
                ?>

                <button class="btn btn-secondary" type="submit" name="placeOrder">Place Order</button>
            </div>
        </div>
    </div>
</section>

<?php include_once "../template/footer.php"  ?>