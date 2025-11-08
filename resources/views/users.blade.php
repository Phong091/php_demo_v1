<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; text-align: left; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; max-width: 900px; margin: 24px auto; }
        h1 { margin-bottom: 16px; }
    </style>
    </head>
<body>
    <h1>Quản lý người dùng</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Tên</th>
                <th>Role</th>
                <th>Ngày sinh</th>
                <th>Tạo lúc</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ (int)($u->role ?? 1) === 0 ? 'Admin' : 'User' }}</td>
                    <td>{{ $u->birthday }}</td>
                    <td>{{ $u->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>



