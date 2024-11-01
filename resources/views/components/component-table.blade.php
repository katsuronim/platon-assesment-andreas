@php
use Carbon\Carbon;
@endphp
<h1 class="py-10 text-3xl font-thin text-center text-white">Hasil Assesment Platon</h1>
<div class="grid grid-cols-2 px-16 mb-5 grd w-fit">
    <h1 class="text-lg font-light text-left text-white ">Nama:</h1>
    <h1 class="text-lg font-light text-left text-white ">Andreas Noah Jati Sesoca</h1>
    <h1 class="text-lg font-light text-left text-white ">Email:</h1>
    <a class="group" href="mailto:rereandreas9@gmail.com?" target="_blank">
        <h1 class="text-lg font-light text-left text-white group-hover:text-blue-500">rereandreas9@gmail.com</h1>
    </a>
    <h1 class="text-lg font-light text-left text-white ">No.Telepon:</h1>
    <a class="group" href="https://wa.me/6281296617031?" target="_blank">
        <h1 class="text-lg font-light text-left text-white group-hover:text-blue-500 ">081296617031</h1>
    </a>
</div>
<div class="flex flex-col items-center justify-center w-full mx-auto">
    <table class="mx-16 my-5 bg-white table-fixed rounded-xl">
        <thead>
            <tr class="border-b">
                <th class="p-5 mx-10 border-r">No. DO Kecil</th>
                <th class="p-5 mx-10 border-r">Tgl DO Kecil</th>
                <th class="p-5 mx-10 border-r">Nama Sopir</th>
                <th class="p-5 mx-10 border-r">Muat</th>
                <th class="p-5 mx-10 border-r">Bongkar</th>
                <th class="p-5 mx-10 border-r">Susut</th>
                <th class="p-5 mx-10 border-r">Toleransi</th>
                <th class="p-5 mx-10 border-r">Susut diatas Toleransi</th>
                <th class="p-5 mx-10 border-r">Denda Susut Sopir</th>
                <th class="p-5 mx-10 border-r">Kontribusi tdk susut</th>
                <th class="p-5 mx-10 border-r">Kontribusi Bonus</th>
                <th class="p-5 mx-10 border-r">Bonus antar teman</th>
                <th class="p-5 mx-10 border-l">isKenaDenda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr class="border-b">
                    <td class="p-5 text-center">{{ $item->do_besar }}/{{ $item->do_kecil }}</td>
                    <td>{{ Carbon::parse($item->tanggal_do_kecil)->format('d-M-Y') }}</td>
                    <td class="p-5 text-center">{{ $item->driver }}</td>
                    <td class="p-5 text-center">{{ $item->netto_muat }}</td>
                    <td class="p-5 text-center">{{ $item->netto_bongkar }}</td>
                    <td class="p-5 text-center">{{ $item->susut }}</td>
                    <td class="p-5 text-center">{{ $item->batasToleransi }}</td>
                    <td class="p-5 text-center">{{ $item->susutToleransi }}</td>
                    <td class="p-5 text-center">
                        {{ $item->dendaSusut != 0 ? 'Rp ' . number_format($item->dendaSusut, 0, ',', '.') : '-' }}
                    </td>
                    <td class="p-5 text-center">{{ $item->kontribusiTidakSusut != 0 ? $item->kontribusiTidakSusut : '-' }}</td>
                    <td class="p-5 text-center">{{ $item->kontribusiBonus}}%</td>
                    <td class="p-5 text-center">{{ $item->bonusAntarTeman }}</td>
                    <td class="p-5 text-center">
                        <span class="{{ $item->isKenaDenda ? 'text-blue-500' : 'text-red-500' }}">
                            {{ $item->isKenaDenda ? 'TRUE' : 'FALSE' }}
                        </span>
                    </td>
                </tr>
            @endforeach
            <tr class="border-t">
                <td class="p-5 font-bold text-center">TOTAL</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">{{ $sumMuat }}</td>
                <td class="p-5 font-bold text-center">{{ $sumBongkar }}</td>
                <td class="p-5 font-bold text-center">{{ $sumSusut }}</td>
                <td class="p-5 font-bold text-center">{{ $sumToleransi }}</td>
                <td class="p-5 font-bold text-center">{{ $sumSusutAtasToleransi }}</td>
                <td class="p-5 font-bold text-center">{{ $sumDendaSusut }}</td>
                <td class="p-5 font-bold text-center">{{ $sumKontribusiTidakSusut }}</td>
                <td class="p-5 font-bold text-center">{{ $sumKontribusiBonus }}</td>
                <td class="p-5 font-bold text-center">{{ $sumBonusAntarTeman }}</td>
            </tr>
            <tr class="border-t">
                <td class="p-5 font-bold text-center">TOLERANSI SUSUT TOTAL 0.25%</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">
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
                <td class="p-5 font-bold text-center">TOTAL SUSUT DI ATAS TOLERANSI</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">
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
                <td class="p-5 font-bold text-center">DENDA FR</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">{{ number_format($dendaFR, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-t">
                <td class="p-5 font-bold text-center">DENDA SUSUT SOPIR</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">{{ $sumDendaSusut }}</td>
            </tr>
            <tr class="border-t">
                <td class="p-5 font-bold text-center">SISA DENDA SUSUT SOPIR</td>
                <td colspan="2"></td>
                <td class="p-5 font-bold text-center">{{ $sisaDendaSusutSopir }}</td>
            </tr>
        </tbody>
    </table>
</div>

