<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Dashboard</h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="javascript:;" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Profile Pengguna </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>

                <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
            </div>
            <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
                <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
                </span>
            </div>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="#" class="btn kt-subheader__btn-daterange" data-toggle="kt-tooltip" title="Tanggal Sekarang" data-placement="left">
                    <span class="kt-subheader__btn-daterange-title">Hari Ini</span>&nbsp;
                    <?php
                    function date_indo($date)
                    {
                        $month = array(
                            1 =>   'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        );
                        $track = explode('-', $date);
                        return $track[2] . ' ' . $month[(int) $track[1]] . ' ' . $track[0];
                    }
                    ?>
                    <span class="kt-subheader__btn-daterange-date"><?= date_indo(date('Y-m-d', time())); ?></span>
                    <i class="flaticon2-calendar-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!--Begin::Dashboard 1-->

        <!--Begin::Section-->
        <div class="row justify-content-center">
            <div class="col-md-7">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Profile Pengguna
                            </h3>
                        </div>
                    </div>

                    <!--begin::Form-->
                    <form class="kt-form" action="<?= base_url('cp-admin/profile'); ?>" method="post">
                        <div class="kt-portlet__body">
                            <?= $this->session->flashdata('notification'); ?>
                            <div class="form-group">
                                <label for="Title">Nama Pengguna</label>
                                <input type="hidden" name="<?= $name; ?>" value="<?= $hash; ?>" />
                                <input type="text" class="form-control" id="Title" name="name" value="<?= $user['name']; ?>">
                                <?php echo form_error('name', '<small class="error" style="color:red; margin-left:5px; margin-bottom:-100px;">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
                                <?php echo form_error('email', '<small class="error" style="color:red; margin-left:5px; margin-bottom:-100px;">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="password">Kata Sandi Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <?php echo form_error('password', '<small class="error" style="color:red; margin-left:5px; margin-bottom:-100px;">', '</small>'); ?>
                            </div>
                            <div class="form-group form-group-last">
                                <label for="confirm">Konfirmasi kata Sandi</label>
                                <input type="password" class="form-control" id="confirm" name="confirm">
                                <?php echo form_error('confirm', '<small class="error" style="color:red; margin-left:5px; margin-bottom:-100px;">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>
        </div>
        <!--End::Dashboard 1-->
    </div>
    <!-- end:: Content -->
</div>