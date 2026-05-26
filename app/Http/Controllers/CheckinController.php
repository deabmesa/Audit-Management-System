
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;

class CheckinController extends Controller
{
    public function checkIn() {
        Checkin::create([
            'user_id' => auth()->id(),
            'check_in' => now()
        ]);
        return back()->with('success', 'Checked in');
    }

    public function checkOut() {
        $record = Checkin::where('user_id', auth()->id())->latest()->first();
        if ($record) {
            $record->update(['check_out' => now()]);
        }
        return back()->with('success', 'Checked out');
    }
}
