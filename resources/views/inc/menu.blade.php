<div class="row">
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link " href="{{url('order')}}" role="tab">Sales</a>
      <div class="dropdown-content">
        <a href="{{url('order')}}">Add Invoice</a>
        <a href="{{url('searchInvoice')}}">Search Invoice</a>
        <!--<a href="#">列印發票</a>-->
        <a href="{{url('preparationlist')}}">Preparation List</a>
        <a href="{{url('checklist')}}">Checklist</a>
        <!--<a href="#">埋數表</a>-->
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('inventory')}}" role="tab">Supply</a>
      <div class="dropdown-content">
        <a href="{{url('receipt')}}">Add Invoice</a>
        <a href="{{url('searchReceipt')}}">Search Invoice</a>
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('stat')}}" role="tab">Statistics</a>
      <div class="dropdown-content">
        <a href="{{url('dailySettlement')}}">Daily Settlement</a>
        <a href="{{url('monthlyStatement')}}">Monthly Settlement</a>
        <!-- <a href="#">報價單(未有)</a> -->
        <a href="{{url('phoneList')}}">Phone List</a>
        <!-- <a href="#">存貨量(未有)</a> -->
        <!-- <a href="#">出貨量(未有)</a> -->
        <!--<a href="#">會計</a>-->
      </div>
  </div>
  <div class="col-lg-12 col-md-3 col-sm-3 dropdown"><a class="custom-nav-link" href="{{url('settings')}}" role="tab">Settings</a>
      <div class="dropdown-content">
        <a href="{{url('customerSettings')}}">Customer</a>
        <a href="{{url('supplierSettings')}}">Supplier</a>
        <a href="{{url('productSettings')}}">Product</a>
        <a href="{{url('districtSettings')}}">District</a>
        <a href="{{url('categorySettings')}}">Category</a>
        <a href="{{url('invoiceSettings')}}">Invoice</a>
        <!--<a href="{{url('register1')}}">帳戶(未有)</a>-->
      </div>
  </div>
</div>
