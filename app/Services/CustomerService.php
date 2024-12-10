<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerService
{
    public function getAllCustomers($page = 1, $limit = 10, $search = null)
    {
        $query = Customer::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone_number', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createCustomer(array $data)
{
    // Ambil tanggal lahir dari data input
    $birthDate = new \DateTime($data['date_of_birth']);
    
    // Gunakan tanggal pembuatan customer sebagai bulan pendaftaran
    $currentDate = new \DateTime(); // Ambil waktu sekarang
    $registrationMonth = $currentDate->format('m'); // dua digit bulan pendaftaran dari hari create
    $registrationYear = $currentDate->format('y');  // dua digit tahun pendaftaran dari hari create
    
    $birthDay = $birthDate->format('d'); // dua digit tanggal lahir
    $birthMonth = $birthDate->format('m'); // dua digit bulan lahir
    $birthYear = $birthDate->format('Y'); // empat digit tahun lahir
 
    // Ambil nomor urut terakhir (misalnya dari database)
    $lastCustomer = \App\Models\Customer::orderByRaw('CAST(SUBSTRING_INDEX(id, " ", -1) AS UNSIGNED) DESC')->first();
    $parts = explode(' ', $lastCustomer->id);
    
    // Ambil bagian terakhir setelah spasi ketiga
    $lastDigit = intval($parts[3]);
    //tambahkan 1 dikit angka
    $sequence = $lastDigit ? ($lastDigit + 1) : 1;
    
    // Gabungkan untuk membentuk id
    $data['id']  = "{$registrationMonth}{$registrationYear}". ' ' ."{$birthDay}{$birthMonth}" . ' ' . "{$birthYear}" . ' ' ."{$sequence}";

    // Buat customer baru dengan data yang sudah diolah
    return Customer::create($data);
}


    public function getCustomerById(string $id)
    {
        return Customer::findOrFail($id);
    }

    public function updateCustomer(string $id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
    }
}
