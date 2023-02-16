<!-- CONTENT -->
<section class="helpPage">
    <div class="container vh70">
        <div class="row py-5" id="failTipContainer">
            <template id="failTip">
                <div class="col-2 ps-4 pt-4">
                    <small><i data-feather="info"></i></small>
                </div>
                <div class="col-10 pe-4 pt-4 text-justify" >
                    <small>
                        {{message}}
                    </small>
                </div>
            </template>
        </div>
        <div class="row py-4">
            <div class="col-12 px-4 text-center">
                <div class="targaField">
                    <p class="subTitle mb-2 text-uppercase">Inserisci targa</p>
                    <!-- Se la targa è valida, mettere la classe "accepted" all'input, altrimenti mettere la classe "denied" -->
                    <input type="text" name="" id="plateInput" placeholder="AA 000 AA" class="">
                </div>
                <div class="buttonStyle bkgRed my-4" id="checkPlate">
                    verifica
                </div> 

                <div class="status notValid my-4" id="failMessage"> 
                    <h5><i data-feather="alert-triangle"></i> Targa non valida</h5>
                </div>

                <div class="status valid my-4" id="successMessage"> 
                    <h5> <i data-feather="check-square"></i>Targa valida</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /CONTENT -->