    <h2>Create New User</h2>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>


        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="role">Role:</label>
            <select name="role" id="role">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>



        <button type="submit">Create</button>
    </form>
