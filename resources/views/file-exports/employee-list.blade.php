<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Employee_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('total_employee')  }}- {{ $data['employees']->count() }}
                    <br>
                    {{ translate('active_employee')  }}- {{ $data['employees']->where('status',1)->count() }}
                    <br>
                    {{ translate('inactive_employee')  }}- {{ $data['employees']->where('status',0)->count() }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{translate('Employee_Image')}}</th>
            <th>{{translate('First_Name')}}</th>
            <th>{{translate('Last_Name')}}</th>
            <th>{{translate('Phone')}}</th>
            <th>{{translate('Email')}}</th>
            <th>{{translate('Role')}}</th>
            <th>{{translate('Zone')}}</th>
            <th>{{translate('Joining_Date')}}</th>
        </thead>
        <tbody>
        @foreach($data['employees'] as $key => $employee)
        <tr>
            <td>{{$key+1}}</td>
            <td></td>
            <td>{{  $employee['f_name']  }}</td>
            <td>{{  $employee['l_name']  }}</td>
            <td>{{  $employee['phone']  }}</td>
            <td>{{  $employee['email']  }}</td>
            <td>{{  $employee->role?$employee->role['name']:translate('messages.role_deleted')  }}</td>
            <td>{{  $employee->zones?$employee->zones->name:translate('messages.all')  }}</td>
            <td>{{ \App\CentralLogics\Helpers::time_date_format($employee->created_at) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
