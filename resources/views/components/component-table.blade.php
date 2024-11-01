@php
use Carbon\Carbon;
@endphp

<style>
    body {
        background-color: #1f2937; /* Tailwind's bg-gray-800 */
        color: white;
        font-family: Arial, sans-serif;
    }
    h1 {
        text-align: center;
        padding: 2.5rem 0;
        font-size: 2rem;
        font-weight: 300;
    }
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        padding: 0 4rem; /* Tailwind's px-16 */
        margin-bottom: 1.25rem; /* Tailwind's mb-5 */
    }
    .grid h1 {
        font-size: 1.125rem; /* Tailwind's text-lg */
        font-weight: 300; /* Tailwind's font-light */
        text-align: left;
    }
    .group a {
        text-decoration: none;
    }
    .group a:hover h1 {
        color: #3b82f6; /* Tailwind's text-blue-500 */
    }
    .table {
        width: 100%;
        margin: 0 4rem; /* Tailwind's mx-16 */
        background-color: white;
        border-radius: 1rem; /* Tailwind's rounded-xl */
        border-collapse: collapse;
    }
    th, td {
        padding: 1.25rem; /* Tailwind's p-5 */
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f9fafb; /* Tailwind's bg-gray-100 */
        font-weight: 400; /* Tailwind's font-normal */
    }
    .border-t {
        border-top: 2px solid #ddd;
    }
    .font-bold {
        font-weight: bold;
    }
    .text-blue-500 {
        color: #3b82f6; /* Tailwind's text-blue-500 */
    }
    .text-red-500 {
        color: #ef4444; /* Tailwind's text-red-500 */
    }
    .border-b {
        border-bottom: 1px solid #ddd;
    }
</style>

<h1>Hasil Assesment Platon</h1>
<div class="grid">
    <h1>Nama:</h1>
    <h1>Andreas Noah Jati Sesoca</h1>
    <h1>Email:</h1>
    <a class="group" href="mailto:rereandreas9@gmail.com?" target="_blank">
        <h1>rereandreas9@gmail.com</h1>
    </a>
    <h1>No.Telepon:</h1>
    <a class="group" href="https://wa.me/6281296617031?" target="_blank">
        <h1>081296617031</h1>
    </a>
</div>
<div class="flex flex-col items-center justify-center w-full mx-auto">
    <table class="table">
        <thead>
            <tr class="border-b">
                <th>No. DO Kecil</th>
                <th>Tgl DO Kecil</th>
                <th>Nama Sopir</th>
                <th>Muat</th>
                <th>Bongkar</th>
                <th>Susut</th>
                <th>Toleransi</th>
                <th>Susut diatas Toleransi</th>
                <th>Denda Susut Sopir</th>
                <th>Kontribusi tdk susut</th>
                <th>Kontribusi Bonus</th>
                <th>Bonus antar teman</th>
                <th>isKenaDenda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr class="border-b">
                    <td>{{ $item->do_besar }}/{{ $item->do_kecil }}</td>
                    <td>{{ Carbon::parse($item->tanggal_do_kecil)->format('d-M-Y') }}</td>
                    <td>{{ $item->driver }}</td>
                    <td>{{ $item->netto_muat }}</td>
                    <td>{{ $item->netto_bongkar }}</td>
                    <td>{{ $item->susut }}</td>
                    <td>{{ $item->batasToleransi }}</td>
                    <td>{{ $item->susutToleransi }}</td>
                    <td>
                        {{ $item->dendaSusut != 0 ? 'Rp ' . number_format($item->dendaSusut, 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $item->kontribusiTidakSusut != 0 ? $item->kontribusiTidakSusut : '-' }}</td>
                    <td>{{ $item->kontribusiBonus}}%</td>
                    <td>
                        <span class="{{ $item->isKenaDenda ? 'text-blue-500' : 'text-red-500' }}">
                            {{ $item->isKenaDenda ? 'TRUE' : 'FALSE' }}
                        </span>
                    </td>
                </tr>
            @endforeach
            <tr class="border-t">
                <td class="font-bold">TOTAL</td>
                <td colspan="2"></td>
                <td class="font-bold">{{ $sumMuat }}</td>
                <td class="font-bold">{{ $sumBongkar }}</td>
                <td class="font-bold">{{ $sumSusut }}</td>
                <td class="font-bold">{{ $sumToleransi }}</td>
                <td class="font-bold">{{ $sumSusutAtasToleransi }}</td>
                <td class="font-bold">{{ $sumDendaSusut }}</td>
                <td class="font-bold">{{ $sumKontribusiTidakSusut }}</td>
                <td class="font-bold">{{ $sumKontribusiBonus }}</td>
                <td class="font-bold">{{ $sumBonusAntarTeman }}</td>
            </tr>
            <tr class="border-t">
                <td class="font-bold">TOLERANSI SUSUT TOTAL 0.25%</td>
                <td colspan="2"></td>
                <td class="font-bold">
                    @php
                        if($sumToleransi < 0){
                            $totalToleransi = $sumToleransi * -1;
                        } else {
                            $totalToleransi = $sumToleransi;
                        }
                    @endphp
                    {{ $totalToleransi }}
                </td>
            </tr>
            <tr class="border-t">
                <td class="font-bold">TOTAL SUSUT DI ATAS TOLERANSI</td>
                <td colspan="2"></td>
                <td class="font-bold">
                    @php
                        if($sumSusutAtasToleransi < 0){
                            $totalSusutAtasToleransi = $sumSusutAtasToleransi * -1;
                        } else {
                            $totalSusutAtasToleransi = $sumSusutAtasToleransi;
                        }
                    @endphp
                    {{ $totalSusutAtasToleransi }}
                </td>
            </tr>
            <tr class="border-t">
                <td class="font-bold">DENDA FR</td>
                <td colspan="2"></td>
                <td class="font-bold">{{ number_format($dendaFR, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-t">
                <td class="font-bold">DENDA SUSUT SOPIR</td>
                <td colspan="2"></td>
                <td class="font-bold">{{ $sumDendaSusut }}</td>
            </tr>
            <tr class="border-t">
                <td class="font-bold">SISA DENDA SUSUT SOPIR</td>
                <td colspan="2"></td>
                <td class="font-bold">{{ $sisaDendaSusutSopir }}</td>
            </tr>
        </tbody>
    </table>
</div>
