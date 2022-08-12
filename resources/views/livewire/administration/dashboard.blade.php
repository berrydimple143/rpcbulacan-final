<div class="row">
    <div class="col-md-12 grid-margin">
        
      <div class="row">
        <div class="col-md-12 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card" style="background-color: #FE51B5; color: #fff;">
                <div class="card-body">
                  <p class="mb-4">{{ strtoupper($todaymsg) }}</p>
                  <p class="fs-30 mb-2">{{ $today }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card" style="background-color: #972969; color: #fff;">
                <div class="card-body">
                  <p class="mb-4">{{ strtoupper($totalmsg) }}</p>
                  <p class="fs-30 mb-2">{{ $total }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
            <div class="row">
                  <?php $stl = 'text-decoration: none; color: #333;';  ?>
                      @foreach($locations as $mun)
                          <?php 
                                $max = App\Http\Controllers\HelperController::getMax();
                                $mcode = $mun->municipality_code_number;
                                $cntr = App\Models\User::where('municipality', $mcode)->count();
                                $percent = (($cntr / $max) * 50); 
                                $per = (string)$percent . "%"; 
                                $rt = config('app.url')."control/registrants/?type=municipality&municipality2=".$mcode."&source=link";
                          ?>
                          <div class="col-md-6 border-right">
                            <div class="table-responsive mb-3 mb-md-0 mt-3">
                              <table class="table table-borderless report-table">
                                <tr>
                                  <td class="text-muted"><a style="{{ $stl }}" href="{{ $rt }}">{{ $mun->municipality_name }}</a></td>
                                  <td class="w-100 px-0">
                                    <div class="progress progress-xl mx-4">
                                      <div class="progress-bar bg-primary-2" role="progressbar" style="width: {{ $per }};" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                  </td>
                                  <td><h5 class="font-weight-bold mb-0">{{ $cntr }}</h5></td>
                                </tr>
                              </table>
                            </div>
                          </div>
                  @endforeach
              
            </div>
    </div>
  </div>