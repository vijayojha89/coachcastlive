<?php
use yii\helpers\Url;

if(\Yii::$app->user->identity->role == 'trainer')
{

?>
<div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['trainer-class/create']); ?>">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.015 512.015">
                                                <g>
                                                    <g>
                                                        <path d="M102.409,0c-42.351,0.009-76.8,34.458-76.8,76.809c0,42.351,34.449,76.792,76.8,76.792c42.351,0,76.8-34.449,76.8-76.8
                                                   C179.209,34.449,144.76,0,102.409,0z M102.409,128.009c-28.279,0-51.2-22.921-51.2-51.2s22.921-51.2,51.2-51.2
                                                   s51.2,22.921,51.2,51.2S130.689,128.009,102.409,128.009z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M486.409,128h-256c-14.14,0-25.6,11.46-25.6,25.6v19.55l25.6,12.8V153.6h256v204.791h-256v-30.43l-25.6-6.391v36.821
                                                   c0,14.14,11.46,25.6,25.6,25.6h105.404l-65.178,108.621c-3.652,6.05-1.673,13.926,4.403,17.553
                                                   c5.999,3.678,13.901,1.698,17.553-4.403l53.018-88.371v81.792c0,7.074,5.726,12.8,12.8,12.8s12.8-5.726,12.8-12.8v-81.792
                                                   l53.026,88.363c2.398,4.002,6.647,6.221,10.974,6.221c2.253,0,4.523-0.572,6.579-1.826c6.076-3.627,8.047-11.503,4.403-17.553
                                                   l-65.178-108.621h105.395c14.14,0,25.6-11.46,25.6-25.6V153.6C512.009,139.46,500.549,128,486.409,128z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M447.506,201.276c-1.929-6.801-9.003-10.803-15.821-8.772l-157.961,45.133c-1.843-1.783-3.908-3.345-6.263-4.531
                                                   l-102.4-51.2c-3.558-1.775-7.475-2.705-11.452-2.705h-102.4c-12.373,0-22.972,8.849-25.19,21.018l-25.6,140.791
                                                   c-1.357,7.467,0.666,15.155,5.53,20.983s12.066,9.199,19.661,9.199h16.358l9.327,117.222c1.058,13.312,12.16,23.569,25.515,23.569
                                                   h51.2c13.047,0,24.004-9.805,25.446-22.767l22.366-201.276l73.984,18.492c2.048,0.512,4.13,0.759,6.204,0.759
                                                   c5.649,0,11.204-1.869,15.753-5.419c6.212-4.847,9.847-12.297,9.847-20.181V261.99l157.124-44.894
                                                   C445.535,215.168,449.46,208.068,447.506,201.276z M256.009,281.6l-102.4-25.6l-25.6,230.392h-51.2l-11.196-140.8H25.609
                                                   l25.6-140.791h38.4v77.875c0,7.074,5.726,12.8,12.8,12.8c7.074,0,12.8-5.726,12.8-12.8V204.8h38.4l102.4,51.2V281.6z" />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Add New Class</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['trainer-video/create']); ?>">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32">
                                                <g>
                                                    <g>
                                                        <path d="M24,14.059V5.584L18.414,0H0v32h24v-0.059c4.499-0.5,7.998-4.309,8-8.941
                                                        C31.998,18.366,28.499,14.557,24,14.059z M17.998,2.413L21.586,6h-3.588C17.998,6,17.998,2.413,17.998,2.413z M2,30V1.998h14
                                                        v6.001h6v6.06c-0.693,0.076-1.361,0.238-2,0.464V12H8v3l-4-3v10l4-3v3h6.059C14.022,22.329,14,22.661,14,23
                                                        c0,2.829,1.308,5.352,3.35,7H2z M23,29.883c-3.801-0.009-6.876-3.084-6.885-6.883c0.009-3.801,3.084-6.876,6.885-6.885
                                                        c3.799,0.009,6.874,3.084,6.883,6.885C29.874,26.799,26.799,29.874,23,29.883z" />
                                                        <polygon points="19,24 27,24 22.998,20 " />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Add New Videos</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['blog/create']); ?>">
                                        <i class="fa fa-feed" aria-hidden="true"></i>
                                            <!-- <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                                                <g>
                                                    <g>
                                                        <path d="M403.234,395.844c-13.611-5.956-29.056-12.689-46.626-20.736l-0.794-0.358l-0.623,0.606
                                                            c-5.879,5.709-12.39,11.11-19.371,16.06l-1.792,1.263l1.988,0.93c22.067,10.257,41.182,18.662,57.353,25.737
                                                            c59.998,26.18,67.576,31.292,67.576,45.559c0,9.796-9.66,21.641-21.641,21.641H72.687c-11.981,0-21.641-11.844-21.641-21.641
                                                            c0-14.268,7.586-19.379,67.576-45.559c16.171-7.074,35.294-15.479,57.353-25.737l1.988-0.93l-1.792-1.263
                                                            c-6.972-4.949-13.491-10.359-19.371-16.06l-0.623-0.606l-0.794,0.358c-17.579,8.038-33.016,14.78-46.626,20.736
                                                            C48.239,422.263,25.6,432.154,25.6,464.913C25.6,489.557,48.043,512,72.687,512h366.626c24.644,0,47.087-22.443,47.087-47.087
                                                            C486.4,432.154,463.761,422.263,403.234,395.844z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M435.2,153.6h-25.6C409.6,68.77,340.83,0,256,0S102.4,68.77,102.4,153.6H76.8c-14.14,0-25.6,11.46-25.6,25.6V256
                                                        c0,14.14,11.46,25.6,25.6,25.6h25.6c7.56,0,14.174-3.422,18.867-8.627c0.777,1.101,1.468,2.219,2.389,3.285
                                                        C141.38,346.411,193.997,397.432,256,397.432c63.505,0,117.052-53.572,133.461-126.353c4.668,6.229,11.759,10.522,20.139,10.522
                                                        h25.6c14.14,0,25.6-11.46,25.6-25.6v-76.8C460.8,165.06,449.34,153.6,435.2,153.6z M102.4,256H76.8v-76.8h25.6V256z M256,371.985
                                                        c-41.412,0-77.611-29.21-97.374-72.559C188.962,312.627,228.727,320,256,320c7.074,0,12.8-5.726,12.8-12.8s-5.726-12.8-12.8-12.8
                                                        c-45.005,0-92.996-17.988-109.107-30.822c-2.654-12.527-4.19-25.651-4.19-39.262c0-81.425,50.833-147.669,113.297-147.669
                                                        s113.297,66.244,113.297,147.669S318.464,371.985,256,371.985z M383.693,156.655C362.53,94.729,313.267,51.2,256,51.2
                                                        c-57.267,0-106.53,43.529-127.693,105.455c-0.026-1.041-0.307-2.014-0.307-3.055c0-70.579,57.421-128,128-128s128,57.421,128,128
                                                        C384,154.641,383.718,155.614,383.693,156.655z M435.2,256h-25.6v-76.8h25.6V256z" />
                                                    </g>
                                                </g>
                                            </svg> -->
                                            <span>Add New Blog</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="pro-rgt-middel">
                            <div class="appo">
                                <ul>
                                    <li><a href="<?php echo Url::to(['trainer/schedules']); ?>">My Schedules</a></li>
                                    <li><a href="<?php echo Url::to(['trainer-class/index']); ?>">Classes</a></li>
                                    <li><a href="<?php echo Url::to(['trainer-video/index']); ?>">Videos</a></li>
                                </ul>
                            </div>
                            </div>


                            

<?php } ?>        

<?php 


if(\Yii::$app->user->identity->role == 'user')
{

?>
<div class="row">

                               
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['trainer-class/index']); ?>">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.015 512.015">
                                                <g>
                                                    <g>
                                                        <path d="M102.409,0c-42.351,0.009-76.8,34.458-76.8,76.809c0,42.351,34.449,76.792,76.8,76.792c42.351,0,76.8-34.449,76.8-76.8
                                                   C179.209,34.449,144.76,0,102.409,0z M102.409,128.009c-28.279,0-51.2-22.921-51.2-51.2s22.921-51.2,51.2-51.2
                                                   s51.2,22.921,51.2,51.2S130.689,128.009,102.409,128.009z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M486.409,128h-256c-14.14,0-25.6,11.46-25.6,25.6v19.55l25.6,12.8V153.6h256v204.791h-256v-30.43l-25.6-6.391v36.821
                                                   c0,14.14,11.46,25.6,25.6,25.6h105.404l-65.178,108.621c-3.652,6.05-1.673,13.926,4.403,17.553
                                                   c5.999,3.678,13.901,1.698,17.553-4.403l53.018-88.371v81.792c0,7.074,5.726,12.8,12.8,12.8s12.8-5.726,12.8-12.8v-81.792
                                                   l53.026,88.363c2.398,4.002,6.647,6.221,10.974,6.221c2.253,0,4.523-0.572,6.579-1.826c6.076-3.627,8.047-11.503,4.403-17.553
                                                   l-65.178-108.621h105.395c14.14,0,25.6-11.46,25.6-25.6V153.6C512.009,139.46,500.549,128,486.409,128z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M447.506,201.276c-1.929-6.801-9.003-10.803-15.821-8.772l-157.961,45.133c-1.843-1.783-3.908-3.345-6.263-4.531
                                                   l-102.4-51.2c-3.558-1.775-7.475-2.705-11.452-2.705h-102.4c-12.373,0-22.972,8.849-25.19,21.018l-25.6,140.791
                                                   c-1.357,7.467,0.666,15.155,5.53,20.983s12.066,9.199,19.661,9.199h16.358l9.327,117.222c1.058,13.312,12.16,23.569,25.515,23.569
                                                   h51.2c13.047,0,24.004-9.805,25.446-22.767l22.366-201.276l73.984,18.492c2.048,0.512,4.13,0.759,6.204,0.759
                                                   c5.649,0,11.204-1.869,15.753-5.419c6.212-4.847,9.847-12.297,9.847-20.181V261.99l157.124-44.894
                                                   C445.535,215.168,449.46,208.068,447.506,201.276z M256.009,281.6l-102.4-25.6l-25.6,230.392h-51.2l-11.196-140.8H25.609
                                                   l25.6-140.791h38.4v77.875c0,7.074,5.726,12.8,12.8,12.8c7.074,0,12.8-5.726,12.8-12.8V204.8h38.4l102.4,51.2V281.6z" />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Search Classes</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['trainer-video/index']); ?>">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32">
                                                <g>
                                                    <g>
                                                        <path d="M24,14.059V5.584L18.414,0H0v32h24v-0.059c4.499-0.5,7.998-4.309,8-8.941
                                                        C31.998,18.366,28.499,14.557,24,14.059z M17.998,2.413L21.586,6h-3.588C17.998,6,17.998,2.413,17.998,2.413z M2,30V1.998h14
                                                        v6.001h6v6.06c-0.693,0.076-1.361,0.238-2,0.464V12H8v3l-4-3v10l4-3v3h6.059C14.022,22.329,14,22.661,14,23
                                                        c0,2.829,1.308,5.352,3.35,7H2z M23,29.883c-3.801-0.009-6.876-3.084-6.885-6.883c0.009-3.801,3.084-6.876,6.885-6.885
                                                        c3.799,0.009,6.874,3.084,6.883,6.885C29.874,26.799,26.799,29.874,23,29.883z" />
                                                        <polygon points="19,24 27,24 22.998,20 " />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Search Videos</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="findclass">
                                        <a href="<?php echo Url::to(['trainer/index']); ?>">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                                                <g>
                                                    <g>
                                                        <path d="M403.234,395.844c-13.611-5.956-29.056-12.689-46.626-20.736l-0.794-0.358l-0.623,0.606
       c-5.879,5.709-12.39,11.11-19.371,16.06l-1.792,1.263l1.988,0.93c22.067,10.257,41.182,18.662,57.353,25.737
       c59.998,26.18,67.576,31.292,67.576,45.559c0,9.796-9.66,21.641-21.641,21.641H72.687c-11.981,0-21.641-11.844-21.641-21.641
       c0-14.268,7.586-19.379,67.576-45.559c16.171-7.074,35.294-15.479,57.353-25.737l1.988-0.93l-1.792-1.263
       c-6.972-4.949-13.491-10.359-19.371-16.06l-0.623-0.606l-0.794,0.358c-17.579,8.038-33.016,14.78-46.626,20.736
       C48.239,422.263,25.6,432.154,25.6,464.913C25.6,489.557,48.043,512,72.687,512h366.626c24.644,0,47.087-22.443,47.087-47.087
       C486.4,432.154,463.761,422.263,403.234,395.844z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M435.2,153.6h-25.6C409.6,68.77,340.83,0,256,0S102.4,68.77,102.4,153.6H76.8c-14.14,0-25.6,11.46-25.6,25.6V256
       c0,14.14,11.46,25.6,25.6,25.6h25.6c7.56,0,14.174-3.422,18.867-8.627c0.777,1.101,1.468,2.219,2.389,3.285
       C141.38,346.411,193.997,397.432,256,397.432c63.505,0,117.052-53.572,133.461-126.353c4.668,6.229,11.759,10.522,20.139,10.522
       h25.6c14.14,0,25.6-11.46,25.6-25.6v-76.8C460.8,165.06,449.34,153.6,435.2,153.6z M102.4,256H76.8v-76.8h25.6V256z M256,371.985
       c-41.412,0-77.611-29.21-97.374-72.559C188.962,312.627,228.727,320,256,320c7.074,0,12.8-5.726,12.8-12.8s-5.726-12.8-12.8-12.8
       c-45.005,0-92.996-17.988-109.107-30.822c-2.654-12.527-4.19-25.651-4.19-39.262c0-81.425,50.833-147.669,113.297-147.669
       s113.297,66.244,113.297,147.669S318.464,371.985,256,371.985z M383.693,156.655C362.53,94.729,313.267,51.2,256,51.2
       c-57.267,0-106.53,43.529-127.693,105.455c-0.026-1.041-0.307-2.014-0.307-3.055c0-70.579,57.421-128,128-128s128,57.421,128,128
       C384,154.641,383.718,155.614,383.693,156.655z M435.2,256h-25.6v-76.8h25.6V256z" />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Find Coaches</span>
                                        </a>
                                    </div>
                                </div>

                               
                               
                            </div>

<div class="pro-rgt-middel">
                            <div class="appo">
                                <ul>
                                    <li><a href="<?php echo Url::to(['user/myappointment']); ?>">My Appointment</a></li>
                                    <li><a href="<?php echo Url::to(['user/myjoinclass']); ?>">My Join Classes</a></li>
                                    <li><a href="<?php echo Url::to(['user/mypurchasedvideo']); ?>">My Purchased Videos</a></li>
                                </ul>
                            </div>
                            </div>
<?php } ?>                            
             

                            