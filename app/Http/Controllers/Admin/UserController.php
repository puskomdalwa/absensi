<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\BulkData;
use App\Models\Departemen;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $role       = Role::all();
        $departemen = Departemen::all();
        return view('admin.user.index', compact('role', 'departemen'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data   = User::select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search, $request) {
                $query->when($request->role_id != "*", function ($query) use ($request) {
                    $query->where('role_id', $request->role_id);
                });
                $query->when($request->departemen_id != "*", function ($query) use ($request) {
                    $query->where('departemen_id', $request->departemen_id);
                });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('username', 'LIKE', "%$search%");
                    $query->orWhere('name', 'LIKE', "%$search%");
                    $query->orWhere('email', 'LIKE', "%$search%");
                    $query->orWhere('jenis_kelamin', 'LIKE', "%$search%");
                });
            })
            ->editColumn('name', function ($row) {
                $assetsPath = asset('photo') . '/';
                if ($row->photo) {
                    // Untuk photo gambar
                    $output = '<img src="' . $assetsPath . $row->photo . '" alt="Avatar" class="rounded-circle">';
                } else {
                    // Untuk photo badge dengan inisial
                    $stateNum = rand(0, 5);
                    $states   = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                    $state    = $states[$stateNum];

                    // Ambil inisial dari nama lengkap
                    preg_match_all('/\b\w/', $row->name, $matches);
                    $initials = isset($matches[0]) ? strtoupper($matches[0][0] . end($matches[0])) : '';

                    $output = '<span class="avatar-initial rounded-circle bg-label-' . $state . '">' . $initials . '</span>';
                }

                return '
                    <div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                            <div class="avatar me-2">
                                ' . $output . '
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="emp_name text-truncate">' . htmlspecialchars($row->name) . '</span>
                            <small class="emp_post text-truncate text-muted">' . htmlspecialchars($row->email ?? 'Unknown') . '</small>
                            <small class="emp_post text-truncate text-muted">' . htmlspecialchars($row->role->akses .' - '.$row->departemen->nama) . '</small>
                        </div>
                    </div>
                ';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-username="' . $row->username . '"
                                        data-name="' . $row->name . '"
                                        data-email="' . $row->email . '"
                                        data-photo="' . $row->photo . '"
                                        data-role_id="' . $row->role_id . '"
                                        data-departemen_id="' . $row->departemen_id . '"
                                        data-jenis_kelamin="' . $row->jenis_kelamin . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="name" value="' . $row->name . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'name'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'username'         => 'required|unique:users',
                'name'             => 'required',
                'email'            => 'nullable|email|unique:users',
                'jenis_kelamin'    => 'required',
                'role_id'          => 'nullable',
                'departemen_id'    => 'nullable',
                'password'         => 'nullable',
                'confirm_password' => 'nullable|same:password',
                'upload_photo'     => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico|max:' . BulkData::maxSizeUpload,
                'photo'            => 'required_with:upload_photo',
            ], [
                'username.unique'           => 'The username is already taken. Please choose another one.',
                'email.unique'              => 'The email has already been registered. Please use a different email.',
                'confirm_password.same'     => 'Password and Confirm Password must match.',
                'confirm_password.required' => 'The confirm password field is required.',
                'photo.required_with'       => 'Photo is required when upload photo is provided.',
            ]);

            $user = new User();
            if ($request->photo) {
                $imageData = $request->photo;

                // Extract the MIME type and the base64-encoded image data
                preg_match('#^data:image/(\w+);base64,#i', $imageData, $matches);

                if (isset($matches[1])) {
                    $extension = $matches[1]; // Extract the file extension (e.g., png, jpeg, gif)

                    $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $image     = base64_decode($imageData);

                    $fileName = uniqid() . '.' . $extension;

                    $path = public_path('photo/' . $fileName);
                    file_put_contents($path, $image);

                    $user->photo = $fileName;
                }
            }

            $user->username      = $request->username;
            $user->name          = $request->name;
            $user->email         = $request->email;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->role_id       = $request->role_id;
            $user->departemen_id = $request->departemen_id;
            if ($request->password) {
                $user->password = \Hash::make($request->password);
            }
            $user->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => implode('<br><br>', array_map('implode', $e->errors())),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            $user = User::findOrFail($request->id);

            $request->validate([
                'id'               => 'required|exists:users,id',
                'username'         => 'required|unique:users,username,' . $user->id,
                'name'             => 'required',
                'email'            => 'nullable|email|unique:users,email,' . $user->id,
                'jenis_kelamin'    => 'required',
                'role_id'          => 'nullable',
                'departemen_id'    => 'nullable',
                'password'         => 'nullable',
                'confirm_password' => 'nullable|same:password',
                'upload_photo'     => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico|max:' . BulkData::maxSizeUpload,
                'photo'            => 'required_with:upload_photo',
            ], [
                'username.unique'           => 'The username is already taken. Please choose another one.',
                'email.unique'              => 'The email has already been registered. Please use a different email.',
                'confirm_password.same'     => 'Password and Confirm Password must match.',
                'confirm_password.required' => 'The confirm password field is required.',
                'photo.required_with'       => 'Photo is required when upload photo is provided.',
            ]);

            if ($request->photo) {
                $imageData = $request->photo;

                // Extract the MIME type and the base64-encoded image data
                preg_match('#^data:image/(\w+);base64,#i', $imageData, $matches);

                if (isset($matches[1])) {
                    $extension = $matches[1]; // Extract the file extension (e.g., png, jpeg, gif)

                    $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $image     = base64_decode($imageData);

                    $fileName = uniqid() . '.' . $extension;

                    $path = public_path('photo/' . $fileName);
                    file_put_contents($path, $image);

                    $user->photo = $fileName;
                }
            }

            $user->username      = $request->username;
            $user->name          = $request->name;
            $user->email         = $request->email;
            $user->role_id       = $request->role_id;
            $user->departemen_id = $request->departemen_id;
            $user->jenis_kelamin = $request->jenis_kelamin;
            if ($request->password) {
                $user->password = \Hash::make($request->password);
            }
            $user->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => implode('<br><br>', array_map('implode', $e->errors())),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = User::findOrFail($request->id);
            if ($data->photo) {
                $path = public_path('photo/' . $data->photo);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $data->delete();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
