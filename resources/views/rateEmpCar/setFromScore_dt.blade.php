<div class="layout-spacing layout-top-spacing pl-3" id="cancel-row">
    <div class="widget-content searchable-container list">
        <div class="col-xl-12 text-sm-right">
            <div class="d-flex bd-highlight">
              
                <div class="mr-auto p-2 bd-highlight">
                    <h4 style="color:">{{ $Title }} <span id="ScoreSubTitle">{{ $SumScore }}</span> คะแนน</h4>
                    <p style="color:red">***คะแนนแต่ละหัวข้อต้องรวมกันไม่เกิน <span id="TotalSubScore">{{ $ScoreTitle }}</span> คะแนน***</p>
                </div>
                <div class="p-2 bd-highlight">
                    <button type="button" class="btn btn-outline-primary" id="AddSubTitle">เพิ่มหัวข้อย่อย</button>
                </div>
            </div>
        </div>
        <div class="searchable-items list">
            <div class="items items-header-section">
                <div class="item-content">
                    <div style="width: 100px;">
                        <h4>ลำดับ</h4>
                    </div>
                    <div style="width: 400px;">
                        <h4>หัวข้อ</h4>
                    </div>
                    <div  style="width: 100px;">
                        <h4>คะแนน</h4>
                    </div>
                    <div class="">
                        <h4>แก้ไข/ลบ</h4>
                    </div>
                </div>
            </div>
            @php
                $i = 1;
            @endphp
            @if (count($RateTitle) > 0)
                @foreach ($RateTitle as $item)
                <div class="items subTitle" data-id="{{ $item->id }}">
                    <div class="item-content">
                        <div style="width: 100px;">
                            <p  class="info-title">{{ $i }}</p>
                        </div>
                        <div class="" style="width: 400px;">
                            <p  class="info-title">{{ $item->Title }}</p>
                        </div>
                        <div  style="width: 100px;">
                            <p  class="info-title">{{ $item->Score }}</p>
                        </div>
                        <div class="action-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit editSubTitle" data-id="{{ $item->id }}"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2  delete-multiple delete" data-id="{{ $item->id }}"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </div>
                    
                    </div>
                </div>
                @php
                    $i++;
                @endphp
                @endforeach
            @endif
        </div>
    </div>
</div>