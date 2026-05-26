@extends('layouts.app')

@section('content')

<style>
.row { margin-top: 10px; }

.form-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.form-card h5 {
    font-weight: 600;
    margin-bottom: 18px;
}

.form-control {
    border-radius: 12px;
    height: 46px;
}

.btn-save {
    background: #FFD230;
    border: none;
    border-radius: 12px;
    height: 46px;
    font-weight: 600;
}

.table th {
    font-size: 13px;
    color: #777;
}
</style>

@php
    $isEdit = isset($editMenu);
@endphp

<div class="container-fluid">

    <div class="row">

        <!-- ================= TABLE ================= -->
        <div class="col-md-8">

            <div class="form-card">
                <h5>Menu Structure</h5>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Route</th>
                            <th>Controller</th>
                            <th>Service</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @php
                    if (!function_exists('renderTree')) {

                        function renderTree($menus, $level = 0)
                        {
                            foreach ($menus as $menu) {

                                echo '<tr>';

                                // MENU NAME
                                echo '<td>' . str_repeat('&nbsp;&nbsp;&nbsp;', $level) . e($menu->name ?? '') . '</td>';

                                // ROUTE
                                echo '<td>' . e($menu->route ?? '-') . '</td>';

                                // CONTROLLER
                                echo '<td>' . e($menu->controller ?? '-') . '</td>';

                                // SERVICE
                                echo '<td>' . e($menu->service ?? '-') . '</td>';

                                // ACTION
                                echo '<td>';

                                echo '<a href="' . route('menus.create', ['edit_id' => $menu->id]) . '"
                                        class="btn btn-sm btn-primary me-1">Edit</a>';

                                echo '<form method="POST" action="' . route('menus.destroy', $menu->id) . '" style="display:inline;">';
                                echo csrf_field();
                                echo method_field('DELETE');

                                echo '<button class="btn btn-sm btn-danger"
                                        onclick="return confirm(\'Delete this menu?\')">Delete</button>';

                                echo '</form>';

                                echo '</td>';

                                echo '</tr>';

                                // CHILDREN
                                if ($menu->children && $menu->children->count()) {
                                    renderTree($menu->children, $level + 1);
                                }
                            }
                        }
                    }

                    renderTree($menus);
                    @endphp

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection