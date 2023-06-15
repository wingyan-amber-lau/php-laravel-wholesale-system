<div class="row">
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link " href="{{url('order')}}" role="tab">出貨</a>
      <div class="dropdown-content">
        <a href="{{url('order')}}">新增發票</a>
        <a href="{{url('searchInvoice')}}">搜尋發票</a>
        <!--<a href="#">列印發票</a>-->
        <a href="{{url('preparationlist')}}">執貨表</a>
        <a href="{{url('checklist')}}">核對清單</a>
        <!--<a href="#">埋數表</a>-->
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('inventory')}}" role="tab">入貨</a>
      <div class="dropdown-content">
        <a href="{{url('receipt')}}">新增入貨單</a>
        <a href="{{url('searchReceipt')}}">搜尋入貨單</a>
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('stat')}}" role="tab">統計</a>
      <div class="dropdown-content">
        <a href="{{url('dailySettlement')}}">每日結算</a>
        <a href="{{url('monthlyStatement')}}">月結單</a>
        <a href="#">報價單(未有)</a>
        <a href="{{url('phoneList')}}">電話清單</a>
        <a href="#">存貨量(未有)</a>
        <a href="#">出貨量(未有)</a>
        <!--<a href="#">會計</a>-->
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('settings')}}" role="tab">設定</a>
      <div class="dropdown-content">
        <a href="{{url('customerSettings')}}">顧客</a>
        <a href="{{url('supplierSettings')}}">供應商</a>
        <a href="{{url('productSettings')}}">貨品</a>
        <a href="{{url('districtSettings')}}">地區</a>
        <a href="{{url('categorySettings')}}">種類</a>
        <a href="{{url('invoiceSettings')}}">發票</a>
        <!--<a href="{{url('register1')}}">帳戶(未有)</a>-->
      </div>
  </div>
</div>
