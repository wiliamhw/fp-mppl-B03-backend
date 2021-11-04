
@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.roles.index') }}';
</script>
@endsection

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <livewire:cms.nav.breadcrumb :items="$this->breadcrumbItems" />

            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Roles</h3>
                    <div class="card-toolbar">
                        @if($this->currentAdmin->can('cms.roles.create'))
                            <button wire:click="performAction('create')" type="button" class="btn mr-1 btn-primary">
                                <i class="fa fa-plus"></i> New Role
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <div class="row mb-4">
                        <div class="col-sm-12 col-md-12 text-md-right text-center">
                            <div class="btn-group" role="group">
                                <button class="btn mr-1 mb-2 btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-eye"></i> Display
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg py-8 px-8">
                                    <div class="row">
                                        @foreach ($this->columns() as $index => $column)
                                        {!! $column->renderVisibilityOption((int) $index) !!}
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="btn-group" role="group">
                                <button class="btn mr-1 mb-2 btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-reply"></i> Export
                                </button>
                                <div class="dropdown-menu">
                                    <a wire:click="export('Xlsx')" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-file-excel mr-2"></i> Excel</a>
                                    <a wire:click="export('Csv')" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-file-csv mr-2"></i> CSV</a>
                                </div>
                            </div>

                            @if($this->currentAdmin->can('cms.roles.delete'))
                                <button class="btn mr-1 mb-2 btn-danger dt-delete-selected" @if (count($this->getSelectedRows()) === 0) disabled @endif>
                                    <i class="fa fa-trash"></i> Delete Selected
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2 text-md-left text-center">
                            Show
                            <select wire:model="perPage" class="custom-select custom-select-sm form-control form-control-sm dt-per-page-selector">
                                @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            entries
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2 text-md-right text-center mb-sm-2">
                            <div>
                                <label>
                                    Search:
                                    <input type="text" wire:model.debounce.700ms="search" class="form-control form-control-sm dt-search-input" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="dt-center-container">
                        <!-- Please consider to add the `table-responsive` class for tables with so many columns -->
                        <table class="table mt-5 mb-5 table-hover table-bordered datatable-sortable table-responsive-sm table-responsive-md table-responsive-lg">
                            <thead>
                            <tr>
                                <th style="width: 60px;">
                                    <label class="checkbox checkbox-outline checkbox-primary">
                                        <input wire:model="selectAllRows" wire:click="toggleSelectAllRows" type="checkbox" name="selectAllRows"><span></span>
                                    </label>
                                </th>

                                @foreach ($this->columns() as $column)
                                {!! $column->renderHeader($sortColumn, $sortDirection) !!}
                                @endforeach

                                <th style="width: 128px;">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($this->data->getCollection() as $item)
                            <tr>
                                <td>
                                    <label class="checkbox checkbox-outline checkbox-primary">
                                        <input wire:model="selectedRows.{{ $item->getKey() }}" type="checkbox" name="selectedRows_{{ $item->getKey() }}"><span></span>
                                    </label>
                                </td>

                                @foreach ($this->columns() as $column)
                                {!! $column->renderCell($item) !!}
                                @endforeach

                                <td>
                                    <button wire:click="performAction('show', '{{ $item->getKey() }}')" class="btn btn-xs btn-icon mr-1 btn-primary">
                                        <i class="fa fa-eye icon-nm"></i>
                                    </button>

                                    @if($this->currentAdmin->can('cms.roles.update'))
                                        <button wire:click="performAction('edit', '{{ $item->getKey() }}')" class="btn btn-xs btn-icon mr-1 btn-warning">
                                            <i class="fa fa-pen icon-nm"></i>
                                        </button>
                                    @endif

                                    @if($this->currentAdmin->can('cms.roles.delete'))
                                        <button class="btn btn-xs btn-icon mr-1 btn-danger dt-delete" data-key="{{ $item->getKey() }}">
                                            <i class="fa fa-trash icon-nm"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if ($this->data->getCollection()->count() === 0)
                                <tr>
                                    <td colspan="999">
                                        <div class="mt-6 mb-6 text-center">
                                            There is no data available in this datatable.
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-3 text-md-left text-center">
                            Showing {{ $this->data->firstItem() }} to {{ $this->data->lastItem() }} of {{ $this->data->total() }} entries
                        </div>
                        <div class="col-sm-12 col-md-8 mb-3 text-md-right text-center">
                            {!! $this->pagination !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

    <script>
        document.addEventListener('livewire:load', function () {
            $('.dt-delete').click(function (event) {
                if (confirm('Do you really wish to continue?')) {
                    let key = $(this).attr('data-key');
                @this.delete(key);
                }
                else {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
            $('.dt-delete-selected').click(function (event) {
                if (confirm('Do you really wish to continue?')) {
                @this.deleteSelected();
                }
                else {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        })
    </script>
</div>
