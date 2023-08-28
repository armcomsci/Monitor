<div class="sidebar-wrapper sidebar-theme">
            
    <nav id="sidebar">
        <div class="shadow-bottom"></div>

        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                <a href="#dashboard" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="dashboard" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('/') }}">หน้าหลัก</a>
                    </li>
                </ul>
            </li>

            <li class="menu">
                <a href="#app" data-toggle="collapse" data-active="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                        <span>Monitor</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="app" data-parent="#accordionExample">
                   
                    @if (auth()->user()->type == 1)
                        <li>
                            <a href="{{ url('/Monitor') }}">ติดตามรถ</a>
                        </li>
                        <li>
                            <a href="{{ url('/ChangeEmpDriv') }}">เปลี่ยนคนรถ</a>
                        </li>
                        <li>
                            <a href="{{ url('/ChangeTimeDriv') }}">แก้ไขเวลาคนรถ</a>
                        </li>
                        <li>
                            <a href="{{ url('/CancelContain') }}">คืนสถานะตู้</a>
                        </li>
                    @else 
                        <li>
                            <a href="{{ url('/MonitorAll') }}">ติดตามรถทั้งหมด</a>
                        </li>    
                        <li>
                            <a href="{{ url('/AddEmpDrive') }}">กำหนดคนรถ</a>
                        </li>
                        <li>
                            <a href="{{ url('/AddCar') }}">กำหนดเที่ยวรถ</a>
                        </li>
                        
                    @endif
                </ul>
            </li>
    
            <li class="menu">
                <a href="#report" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <span>รายงาน</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="report" data-parent="#accordionExample">
                    @if (auth()->user()->type != 1)
                    <li>
                        <a href="{{ url('/ReportJobClose') }}">งานที่ปิดแล้วทั้งหมด</a>
                    </li>
                    <li>
                        <a href="{{ url('/AdminConfirmImg') }}">ยืนยันตำแหน่งร้านค้า</a>
                    </li>
                    <li>
                        <a href="{{ url('/ReportScore') }}">รายงานคะแนน</a>
                    </li>
                    <li>
                        <a href="{{ url('/ReportRemark') }}">รายงานหมายเหตุ</a>
                    </li>
                    <li>
                        <a href="{{ url('/ReportCustConfirm') }}">รายงานรูปภาพร้านค้า</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('/ReportEmpDriver') }}">จำนวนเที่ยวของคนรถ</a>
                    </li>
                </ul>
            </li>
             
        </ul>
        
    </nav>

</div>
