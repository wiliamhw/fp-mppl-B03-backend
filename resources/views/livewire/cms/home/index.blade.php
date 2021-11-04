
@section('additional_scripts')
<script type="text/javascript">
window.resourceUrl = '{{ url('/cms') }}';
</script>
@endsection

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <livewire:cms.nav.breadcrumb :items="null" />

            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Home Dashboard</h3>
                    <div class="card-toolbar">
                        <!--
                        <button class="btn btn-primary">Test button</button>
                        -->
                    </div>
                </div>
                <div class="card-body">
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
