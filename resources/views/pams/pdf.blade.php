<h1>PAMS Report Export</h1>
<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead><tr><th>Audit Name</th><th>Status</th><th>Report Date</th></tr></thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row->AUDIT_NAME }}</td>
            <td>{{ $row->STATUS }}</td>
            <td>{{ $row->REPORT_DATE }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
