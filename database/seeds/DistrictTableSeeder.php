<?php

use Illuminate\Database\Seeder;
use App\District;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('system_dist_mast')->delete();
        District::create( [
            'id'=>1,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'फुङलिङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>2,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'आठराई त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>3,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'सिदिङ्वा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>4,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'फक्ताङलुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>5,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'मिक्वाखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>6,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'मेरिङदेन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>7,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'मैवाखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>8,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'याङवरक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>9,
            'district_id'=>'1',
            'district_name_np'=>'ताप्लेजुङ',
            'district_name'=>'Taplejung',
            'mun_vdc'=>'सिरीजङ्घा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>10,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'फिदिम नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>11,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'फालेलुंग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>12,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'फाल्गुनन्द गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>13,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'हिलिहाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>14,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'कुम्मायक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>15,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'मिक्लाजुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>16,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'तुम्बेवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>17,
            'district_id'=>'2',
            'district_name_np'=>'पाँचथर',
            'district_name'=>'Panchthar',
            'mun_vdc'=>'याङवरक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>18,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'ईलाम नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>19,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'देउमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>20,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'माई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>21,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'सूर्योदय नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>22,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'फाकफोकथुम गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>23,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'चुलाचुली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>24,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'माईजोगमाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>25,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'माङसेबुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>26,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'रोङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>27,
            'district_id'=>'3',
            'district_name_np'=>'ईलाम',
            'district_name'=>'Iilam',
            'mun_vdc'=>'सन्दकपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>28,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'मेचीनगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>29,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'दमक नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>30,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'कन्काई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>31,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'भद्रपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>32,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'अर्जुनधारा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>33,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'शिवशताक्षी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>34,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'गौरादह नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>35,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'विर्तामोड नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>36,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'कमल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>37,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'गौरीगंज गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>38,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'बाह्रदशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>39,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'झापा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>40,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'बुद्धशान्ति गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>41,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'हल्दिवारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>42,
            'district_id'=>'4',
            'district_name_np'=>'झापा',
            'district_name'=>'Jhapa',
            'mun_vdc'=>'कचनकवल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>43,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'विराटनगर महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>44,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'बेलवारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>45,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'लेटाङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>46,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'पथरी शनिश्चरे नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>47,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'रंगेली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>48,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'रतुवामाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>49,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'सुनवर्षि नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>50,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'उर्लावारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>51,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'सुन्दरहरैचा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>52,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'बुढीगंगा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>53,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'धनपालथान गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>54,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'ग्रामथान गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>55,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'जहदा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>56,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'कानेपोखरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>57,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'कटहरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>58,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'केरावारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>59,
            'district_id'=>'5',
            'district_name_np'=>'मोरंग',
            'district_name'=>'Morong',
            'mun_vdc'=>'मिक्लाजुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>60,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'ईटहरी उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>61,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'धरान उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>62,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'ईनरुवा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>63,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'दुहवी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>64,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'रामधुनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>65,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'बराह नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>66,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'देवानगञ्ज गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>67,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'कोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>68,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'गढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>69,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'बर्जु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>70,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'भोक्राहा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>71,
            'district_id'=>'6',
            'district_name_np'=>'सुनसरी',
            'district_name'=>'Sunsari',
            'mun_vdc'=>'हरिनगरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>72,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'पाख्रिबास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>73,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'धनकुटा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>74,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'महालक्ष्मी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>75,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'साँगुरीगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>76,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'खाल्सा छिन्ताङ सहिदभूमि गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>77,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'छथर जोरपाटी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>78,
            'district_id'=>'7',
            'district_name_np'=>'धनकुटा',
            'district_name'=>'Dhankuta',
            'mun_vdc'=>'चौविसे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>79,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'म्याङलुङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>80,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'लालीगुराँस नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>81,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'आठराई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>82,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'छथर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>83,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'फेदाप गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>84,
            'district_id'=>'8',
            'district_name_np'=>'तेहथुम',
            'district_name'=>'Therathum',
            'mun_vdc'=>'मेन्छयायेम गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>85,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'चैनपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>86,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'धर्मदेवी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>87,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'खाँदवारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>88,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'मादी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>89,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'पाँचखपन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>90,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'भोटखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>91,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'चिचिला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>92,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'मकालु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>93,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'सभापोखरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>94,
            'district_id'=>'9',
            'district_name_np'=>'संखुवासभा',
            'district_name'=>'Sankhuwasava',
            'mun_vdc'=>'सिलीचोङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>95,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'भोजपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>96,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'षडानन्द नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>97,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'ट्याम्केमैयुम गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>98,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'रामप्रसाद राई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>99,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'अरुण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>100,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'पौवादुङमा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>101,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'साल्पासिलिछो गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>102,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'आमचोक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>103,
            'district_id'=>'10',
            'district_name_np'=>'भोजपुर',
            'district_name'=>'Bhojpur',
            'mun_vdc'=>'हतुवागढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>104,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'सोलुदुधकुण्ड नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>105,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'दुधकोसी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>106,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'खुम्वु पासाङल्हमु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>107,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'दुधकौशिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>108,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'नेचासल्यान गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>109,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'माहाकुलुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>110,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'लिखु पिके गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>111,
            'district_id'=>'11',
            'district_name_np'=>'सोलुखुम्बु',
            'district_name'=>'Solukhambu',
            'mun_vdc'=>'सोताङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>112,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'सिद्दिचरण नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>113,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'खिजिदेम्बा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>114,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'चम्पादेवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>115,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'चिशंखुगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>116,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'मानेभञ्याङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>117,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'मोलुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>118,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'लिखु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>119,
            'district_id'=>'12',
            'district_name_np'=>'ओखलढुंगा',
            'district_name'=>'Okahdhunga',
            'mun_vdc'=>'सुनकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>120,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'हलेसी तुवाचुङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>121,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'रुपाकोट मझुवागढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>122,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'ऐसेलुखर्क गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>123,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'लामीडाँडा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>124,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'जन्तेढुंगा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>125,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'खोटेहाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>126,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'केपिलासगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>127,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'दिप्रुङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>128,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'साकेला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>129,
            'district_id'=>'13',
            'district_name_np'=>'खोटाङ',
            'district_name'=>'khotang',
            'mun_vdc'=>'वराहपोखरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>130,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'कटारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>131,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'चौदण्डीगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>132,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'त्रियुगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>133,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'वेलका नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>134,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'उदयपुरगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>135,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'ताप्ली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>136,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'रौतामाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>137,
            'district_id'=>'14',
            'district_name_np'=>'उदयपुर',
            'district_name'=>'Udayapur',
            'mun_vdc'=>'सुनकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'1'
        ] );

        District::create( [
            'id'=>138,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'राजविराज नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>139,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'कञ्चनरुप नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>140,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'डाक्नेश्वरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>141,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'बोदेबरसाईन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>142,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'खडक नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>143,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'शम्भुनाथ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>144,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'सुरुङ्‍गा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>145,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'हनुमाननगर कङ्‌कालिनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>146,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'सप्तकोशी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>147,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'अग्निसाइर कृष्णासवरन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>148,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'छिन्नमस्ता गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>149,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'महादेवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>150,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'तिरहुत गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>151,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'तिलाठी कोईलाडी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>152,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'रुपनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>153,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'बेल्ही चपेना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>154,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'बिष्णुपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>155,
            'district_id'=>'15',
            'district_name_np'=>'सप्तरी',
            'district_name'=>'Saptari',
            'mun_vdc'=>'बलान-बिहुल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>156,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'लहान नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>157,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'धनगढीमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>158,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'सिरहा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>159,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'गोलबजार नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>160,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'मिर्चैयाँ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>161,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'कल्याणपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>162,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'कर्जन्हा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>163,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'सुखीपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>164,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'भगवानपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>165,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'औरही गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>166,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'विष्णुपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>167,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'बरियारपट्टी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>168,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'लक्ष्मीपुर पतारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>169,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'नरहा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>170,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'सखुवानान्कारकट्टी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>171,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'अर्नमा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>172,
            'district_id'=>'16',
            'district_name_np'=>'सिराहा',
            'district_name'=>'Siraha',
            'mun_vdc'=>'नवराजपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>173,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'जनकपुर उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>174,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'क्षिरेश्वरनाथ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>175,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'गणेशमान चारनाथ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>176,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'धनुषाधाम नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>177,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'नगराइन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>178,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'विदेह नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>179,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'मिथिला नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>180,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'शहीदनगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>181,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'सबैला नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>182,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'कमला नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>183,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'मिथिला बिहारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>184,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'हंसपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>185,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'जनकनन्दिनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>186,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'बटेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>187,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'मुखियापट्टी मुसहरमिया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>188,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'लक्ष्मीनिया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>189,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'औरही गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>190,
            'district_id'=>'17',
            'district_name_np'=>'धनुषा',
            'district_name'=>'Dhanusa',
            'mun_vdc'=>'धनौजी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>191,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'जलेश्वर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>192,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'बर्दिबास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>193,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'गौशाला नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>194,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'लोहरपट्टी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>195,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'रामगोपालपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>196,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'मनरा शिसवा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>197,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'मटिहानी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>198,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'भँगाहा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>199,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'बलवा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>200,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'औरही नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );

        District::create( [
            'id'=>201,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'एकडारा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>202,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'सोनमा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>203,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'साम्सी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>204,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'महोत्तरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>205,
            'district_id'=>'18',
            'district_name_np'=>'महोत्तरी',
            'district_name'=>'Mahotari',
            'mun_vdc'=>'पिपरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>206,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'ईश्वरपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>207,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'मलंगवा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>208,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'लालबन्दी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>209,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'हरिपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>210,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'हरिपुर्वा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>211,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'हरिवन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>212,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'बरहथवा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>213,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'बलरा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>214,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'गोडैटा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>215,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'बागमती नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>216,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'कविलासी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>217,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'चक्रघट्टा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>218,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'चन्द्रनगर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>219,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'धनकौल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>220,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'ब्रह्मपुरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>221,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'रामनगर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>222,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'विष्णु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>223,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'कौडेना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>224,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'पर्सा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>225,
            'district_id'=>'19',
            'district_name_np'=>'सर्लाही',
            'district_name'=>'Sarlahi',
            'mun_vdc'=>'बसबरीया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>226,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'कमलामाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>227,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'दुधौली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>228,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'गोलन्जर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>229,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'घ्याङलेख गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>230,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'तीनपाटन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>231,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'फिक्कल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>232,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'मरिण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>233,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'सुनकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>234,
            'district_id'=>'20',
            'district_name_np'=>'सिन्धुली',
            'district_name'=>'Sindhuli',
            'mun_vdc'=>'हरिहरपुरगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>235,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'मन्थली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>236,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'रामेछाप नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>237,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'उमाकुण्ड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>238,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'खाँडादेवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>239,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'गोकुलगङ्गा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>240,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'दोरम्बा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>241,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'लिखु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>242,
            'district_id'=>'21',
            'district_name_np'=>'रामेछाप',
            'district_name'=>'Ramechap',
            'mun_vdc'=>'सुनापती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>243,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'जिरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>244,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'भिमेश्वर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>245,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'कालिन्चोक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>246,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'गौरीशङ्कर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>247,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'तामाकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>248,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'मेलुङ्ग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>249,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'विगु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>250,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'वैतेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>251,
            'district_id'=>'22',
            'district_name_np'=>'दोलखा',
            'district_name'=>'Dolakha',
            'mun_vdc'=>'शैलुङ्ग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>252,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'चौतारा साँगाचोकगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>253,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'बाह्रविसे नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>254,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'मेलम्ची नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>255,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'ईन्द्रावती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>256,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'जुगल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>257,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'पाँचपोखरी थाङपाल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>258,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'बलेफी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>259,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'भोटेकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>260,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'लिसङ्खु पाखर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>261,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'सुनकोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>262,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'हेलम्बु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>263,
            'district_id'=>'23',
            'district_name_np'=>'सिन्धुपाल्चोक',
            'district_name'=>'Sindhupalchok',
            'mun_vdc'=>'त्रिपुरासुन्दरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>264,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'धुलिखेल नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>265,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'बनेपा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>266,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'पनौती नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>267,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'पाँचखाल नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>268,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'नमोबुद्ध नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>269,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'मण्डनदेउपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>270,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'खानीखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>271,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'चौंरीदेउराली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>272,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'तेमाल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>273,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'बेथानचोक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>274,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'भुम्लु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>275,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'महाभारत गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>276,
            'district_id'=>'24',
            'district_name_np'=>'काभ्रेपलान्चोक',
            'district_name'=>'Kavarepalanchok',
            'mun_vdc'=>'रोशी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>277,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'ललितपुर महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>278,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'गोदावरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>279,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'महालक्ष्मी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>280,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'कोन्ज्योसोम गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>281,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'बागमती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>282,
            'district_id'=>'25',
            'district_name_np'=>'ललितपुर',
            'district_name'=>'Lalitpur',
            'mun_vdc'=>'महाङ्काल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>283,
            'district_id'=>'26',
            'district_name_np'=>'भक्तपुर',
            'district_name'=>'Bhaktapur',
            'mun_vdc'=>'चाँगुनारायण नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>284,
            'district_id'=>'26',
            'district_name_np'=>'भक्तपुर',
            'district_name'=>'Bhaktapur',
            'mun_vdc'=>'भक्तपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>285,
            'district_id'=>'26',
            'district_name_np'=>'भक्तपुर',
            'district_name'=>'Bhaktapur',
            'mun_vdc'=>'मध्यपुर थिमी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>286,
            'district_id'=>'26',
            'district_name_np'=>'भक्तपुर',
            'district_name'=>'Bhaktapur',
            'mun_vdc'=>'सूर्यविनायक नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>287,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'काठमाण्डौं महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>288,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'कागेश्वरी मनोहरा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>289,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'कीर्तिपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>290,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'गोकर्णेश्वर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>291,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'चन्द्रागिरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>292,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'टोखा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>293,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'तारकेश्वर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>294,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'दक्षिणकाली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>295,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'नागार्जुन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>296,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'बुढानिलकण्ठ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>297,
            'district_id'=>'27',
            'district_name_np'=>'काठमाण्डौ',
            'district_name'=>'Kathmandu',
            'mun_vdc'=>'शङ्खरापुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>298,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'विदुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>299,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'बेलकोटगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>300,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'ककनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>301,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'किस्पाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>302,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'तादी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>303,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'तारकेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>304,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'दुप्चेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>305,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'पञ्चकन्या गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>306,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'लिखु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>307,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'मेघाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>308,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'शिवपुरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>309,
            'district_id'=>'28',
            'district_name_np'=>'नुवाकोट',
            'district_name'=>'Nuwakot',
            'mun_vdc'=>'सुर्यगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>310,
            'district_id'=>'29',
            'district_name_np'=>'रसुवा',
            'district_name'=>'Rasuwa',
            'mun_vdc'=>'उत्तरगया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>311,
            'district_id'=>'29',
            'district_name_np'=>'रसुवा',
            'district_name'=>'Rasuwa',
            'mun_vdc'=>'कालिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>312,
            'district_id'=>'29',
            'district_name_np'=>'रसुवा',
            'district_name'=>'Rasuwa',
            'mun_vdc'=>'गोसाईकुण्ड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>313,
            'district_id'=>'29',
            'district_name_np'=>'रसुवा',
            'district_name'=>'Rasuwa',
            'mun_vdc'=>'नौकुण्ड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>314,
            'district_id'=>'29',
            'district_name_np'=>'रसुवा',
            'district_name'=>'Rasuwa',
            'mun_vdc'=>'पार्वतीकुण्ड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>315,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'धुनीबेंशी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>316,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'निलकण्ठ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>317,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'खनियाबास गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>318,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'गजुरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>319,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'गल्छी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>320,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'गङ्गाजमुना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>321,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'ज्वालामूखी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>322,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'थाक्रे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>323,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'नेत्रावति गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>324,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'बेनीघाट रोराङ्ग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>325,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'रुवी भ्याली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>326,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'सिद्धलेक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>327,
            'district_id'=>'30',
            'district_name_np'=>'धादिङ',
            'district_name'=>'Dhading',
            'mun_vdc'=>'त्रिपुरासुन्दरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>328,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'हेटौडा उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>329,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'थाहा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>330,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'इन्द्रसरोबर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>331,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'कैलाश गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>332,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'बकैया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>333,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'बाग्मति गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>334,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'भिमफेदी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>335,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'मकवानपुरगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>336,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'मनहरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>337,
            'district_id'=>'31',
            'district_name_np'=>'मकवानपुर',
            'district_name'=>'Makawanpur',
            'mun_vdc'=>'राक्सिराङ्ग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>338,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'चन्द्रपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>339,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'गरुडा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>340,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'गौर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>341,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'बौधीमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>342,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'बृन्दावन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>343,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'देवाही गोनाही नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>344,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'गढीमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>345,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'गुजरा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>346,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'कटहरिया नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>347,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'माधव नारायण नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>348,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'मौलापुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>349,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'फतुवाबिजयपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>350,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'ईशनाथ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>351,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'परोहा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>352,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'राजपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>353,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'राजदेवी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>354,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'दुर्गा भगवती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>355,
            'district_id'=>'32',
            'district_name_np'=>'रौतहट',
            'district_name'=>'Rautahat',
            'mun_vdc'=>'यमुनामाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>356,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'कलैया उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>357,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'जीतपुर सिमरा उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>358,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'कोल्हवी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>359,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'निजगढ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>360,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'महागढीमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>361,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'सिम्रौनगढ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>362,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'पचरौता नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>363,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'आदर्श कोटवाल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>364,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'करैयामाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>365,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'देवताल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>366,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'परवानीपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>367,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'प्रसौनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>368,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'फेटा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>369,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'बारागढीगाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>370,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'सुवर्ण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>371,
            'district_id'=>'33',
            'district_name_np'=>'वारा',
            'district_name'=>'Bara',
            'mun_vdc'=>'विश्रामपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>372,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'बिरगंज महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>373,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'पोखरिया नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>374,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'बहुदरमाई नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>375,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'पर्सागढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>376,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'ठोरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>377,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'जगरनाथपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>378,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'धोबीनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>379,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'छिपहरमाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>380,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'पकाहा मैनपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>381,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'बिन्दबासिनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>382,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'सखुवा प्रसौनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>383,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'पटेर्वा सुगौली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>384,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'कालिकामाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>385,
            'district_id'=>'34',
            'district_name_np'=>'पर्सा',
            'district_name'=>'Parsa',
            'mun_vdc'=>'जिरा भवानी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'2'
        ] );



        District::create( [
            'id'=>386,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'भरतपुर महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>387,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'कालिका नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>388,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'खैरहनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>389,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'माडी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>390,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'रत्ननगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>391,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'राप्ती नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>392,
            'district_id'=>'35',
            'district_name_np'=>'चितवन',
            'district_name'=>'Chitwan',
            'mun_vdc'=>'इच्छाकामना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'3'
        ] );



        District::create( [
            'id'=>393,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'गोरखा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>394,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'पालुङटार नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>395,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'सुलीकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>396,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'सिरानचोक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>397,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'अजिरकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>398,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'आरूघाट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>399,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'गण्डकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>400,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'चुमनुव्री गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>401,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'धार्चे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>402,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'भिमसेन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>403,
            'district_id'=>'36',
            'district_name_np'=>'गोरखा',
            'district_name'=>'Gorkha',
            'mun_vdc'=>'शहिद लखन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>404,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'बेसीशहर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>405,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'मध्यनेपाल नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>406,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'रार्इनास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>407,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'सुन्दरबजार नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>408,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'क्व्होलासोथार गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>409,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'दूधपोखरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>410,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'दोर्दी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>411,
            'district_id'=>'37',
            'district_name_np'=>'लमजुङ',
            'district_name'=>'Lamjung',
            'mun_vdc'=>'मर्स्याङदी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>412,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'भानु नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>413,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'भिमाद नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>414,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'व्यास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>415,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'शुक्लागण्डकी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>416,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'आँबुखैरेनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>417,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'ऋषिङ्ग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>418,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'घिरिङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>419,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'देवघाट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>420,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'म्याग्दे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>421,
            'district_id'=>'38',
            'district_name_np'=>'तनहुँ',
            'district_name'=>'Tanahu',
            'mun_vdc'=>'वन्दिपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>422,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'गल्याङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>423,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'चापाकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>424,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'पुतलीबजार नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>425,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'भीरकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>426,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'वालिङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>427,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'अर्जुनचौपारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>428,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'आँधिखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>429,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'कालीगण्डकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>430,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'फेदीखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>431,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'बिरुवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>432,
            'district_id'=>'39',
            'district_name_np'=>'स्याङजा',
            'district_name'=>'Sangja',
            'mun_vdc'=>'हरिनास गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>433,
            'district_id'=>'40',
            'district_name_np'=>'कास्की',
            'district_name'=>'Kaski',
            'mun_vdc'=>'पोखरा लेखनाथ महानगरपालिका',
            'type'=>'महानगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>434,
            'district_id'=>'40',
            'district_name_np'=>'कास्की',
            'district_name'=>'Kaski',
            'mun_vdc'=>'अन्नपूर्ण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>435,
            'district_id'=>'40',
            'district_name_np'=>'कास्की',
            'district_name'=>'Kaski',
            'mun_vdc'=>'माछापुच्छ्रे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>436,
            'district_id'=>'40',
            'district_name_np'=>'कास्की',
            'district_name'=>'Kaski',
            'mun_vdc'=>'मादी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>437,
            'district_id'=>'40',
            'district_name_np'=>'कास्की',
            'district_name'=>'Kaski',
            'mun_vdc'=>'रूपा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>438,
            'district_id'=>'41',
            'district_name_np'=>'मनाङ',
            'district_name'=>'Manang',
            'mun_vdc'=>'चामे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>439,
            'district_id'=>'41',
            'district_name_np'=>'मनाङ',
            'district_name'=>'Manang',
            'mun_vdc'=>'नारफू गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>440,
            'district_id'=>'41',
            'district_name_np'=>'मनाङ',
            'district_name'=>'Manang',
            'mun_vdc'=>'नाशोङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>441,
            'district_id'=>'41',
            'district_name_np'=>'मनाङ',
            'district_name'=>'Manang',
            'mun_vdc'=>'नेस्याङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>442,
            'district_id'=>'42',
            'district_name_np'=>'मुस्ताङ',
            'district_name'=>'Mustang',
            'mun_vdc'=>'घरपझोङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>443,
            'district_id'=>'42',
            'district_name_np'=>'मुस्ताङ',
            'district_name'=>'Mustang',
            'mun_vdc'=>'थासाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>444,
            'district_id'=>'42',
            'district_name_np'=>'मुस्ताङ',
            'district_name'=>'Mustang',
            'mun_vdc'=>'दालोमे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>445,
            'district_id'=>'42',
            'district_name_np'=>'मुस्ताङ',
            'district_name'=>'Mustang',
            'mun_vdc'=>'लोमन्थाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>446,
            'district_id'=>'42',
            'district_name_np'=>'मुस्ताङ',
            'district_name'=>'Mustang',
            'mun_vdc'=>'वाह्रगाउँ मुक्तिक्षेत्र गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>447,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'बेनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>448,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'अन्नपूर्ण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>449,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'धवलागिरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>450,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'मंगला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>451,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'मालिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>452,
            'district_id'=>'43',
            'district_name_np'=>'म्याग्दी',
            'district_name'=>'Magdi',
            'mun_vdc'=>'रघुगंगा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>453,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'कुश्मा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>454,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'फलेवास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>455,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'जलजला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>456,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'पैयूं गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>457,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'महाशिला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>458,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'मोदी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>459,
            'district_id'=>'44',
            'district_name_np'=>'पर्वत',
            'district_name'=>'Parbat',
            'mun_vdc'=>'विहादी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>460,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'बागलुङ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>461,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'गल्कोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>462,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'जैमूनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>463,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'ढोरपाटन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>464,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'वरेङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>465,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'काठेखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>466,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'तमानखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>467,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'ताराखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>468,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'निसीखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>469,
            'district_id'=>'45',
            'district_name_np'=>'वाग्लुङ',
            'district_name'=>'Baglung',
            'mun_vdc'=>'वडिगाड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>470,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'मुसिकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>471,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'रेसुङ्गा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>472,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'ईस्मा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>473,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'कालीगण्डकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>474,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'गुल्मी दरबार गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>475,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'सत्यवती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>476,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'चन्द्रकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>477,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'रुरु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>478,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'छत्रकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>479,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'धुर्कोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>480,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'मदाने गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>481,
            'district_id'=>'46',
            'district_name_np'=>'गुल्मी',
            'district_name'=>'Gulmi',
            'mun_vdc'=>'मालिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>482,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'रामपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>483,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'तानसेन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>484,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'निस्दी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>485,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'पूर्वखोला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>486,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'रम्भा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>487,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'माथागढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>488,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'तिनाउ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>489,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'बगनासकाली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>490,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'रिब्दिकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>491,
            'district_id'=>'47',
            'district_name_np'=>'पाल्पा',
            'district_name'=>'Palpa',
            'mun_vdc'=>'रैनादेवी छहरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>492,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'बुटवल उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>493,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'देवदह नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>494,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'लुम्बिनी सांस्कृतिक नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>495,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'सैनामैना नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>496,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'सिद्धार्थनगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>497,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'तिलोत्तमा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>498,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'गैडहवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>499,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'कन्चन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>500,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'कोटहीमाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>501,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'मर्चवारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>502,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'मायादेवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>503,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'ओमसतिया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>504,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'रोहिणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>505,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'सम्मरीमाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>506,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'सियारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>507,
            'district_id'=>'48',
            'district_name_np'=>'रुपन्देही',
            'district_name'=>'Rupendehi',
            'mun_vdc'=>'शुद्धोधन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>508,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'कपिलवस्तु नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>509,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'बुद्धभूमी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>510,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'शिवराज नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>511,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'महाराजगंज नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>512,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'कृष्णनगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>513,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'बाणगंगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>514,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'मायादेवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>515,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'यसोधरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>516,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'सुद्धोधन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>517,
            'district_id'=>'49',
            'district_name_np'=>'कपिलबस्तु',
            'district_name'=>'Kapilbustu',
            'mun_vdc'=>'विजयनगर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>518,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'सन्धिखर्क नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>519,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'शितगंगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>520,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'भूमिकास्थान नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>521,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'छत्रदेव गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>522,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'पाणिनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>523,
            'district_id'=>'50',
            'district_name_np'=>'अर्घाखाँची',
            'district_name'=>'Argakhachi',
            'mun_vdc'=>'मालारानी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>524,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'प्यूठान नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>525,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'स्वर्गद्वारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>526,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'गौमुखी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>527,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'माण्डवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>528,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'सरुमारानी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>529,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'मल्लरानी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>530,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'नौवहिनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>531,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'झिमरुक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>532,
            'district_id'=>'51',
            'district_name_np'=>'प्यूठान',
            'district_name'=>'Puthun',
            'mun_vdc'=>'ऐरावती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>533,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'रोल्पा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>534,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>535,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'दुईखोली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>536,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'माडी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>537,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'रुन्टीगढी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>538,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'लुङग्री गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>539,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'सुकिदह गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>540,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'सुनछहरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>541,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'सुवर्णावती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>542,
            'district_id'=>'52',
            'district_name_np'=>'रोल्पा',
            'district_name'=>'Rolpa',
            'mun_vdc'=>'थवाङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>543,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'मुसिकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>544,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'चौरजहारी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>545,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'आठबिसकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>546,
            'district_id'=>'54',
            'district_name_np'=>'रुकुम (पूर्वी)',
            'district_name'=>'Rukum (East)',
            'mun_vdc'=>'पुथा उत्तरगंगा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>547,
            'district_id'=>'54',
            'district_name_np'=>'रुकुम (पूर्वी)',
            'district_name'=>'Rukum (East)',
            'mun_vdc'=>'भूमे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>548,
            'district_id'=>'54',
            'district_name_np'=>'रुकुम (पूर्वी)',
            'district_name'=>'Rukum (East)',
            'mun_vdc'=>'सिस्ने गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>549,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'बाँफिकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>550,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>551,
            'district_id'=>'53',
            'district_name_np'=>'रुकुम (पश्चिम)',
            'district_name'=>'Rukum (West)',
            'mun_vdc'=>'सानी भेरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>552,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'शारदा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>553,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'बागचौर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>554,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'बनगाड कुपिण्डे नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>555,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'कालिमाटी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>556,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>557,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'कपुरकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>558,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'छत्रेश्वरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>559,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'ढोरचौर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>560,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'कुमाखमालिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>561,
            'district_id'=>'55',
            'district_name_np'=>'सल्यान',
            'district_name'=>'Salyan',
            'mun_vdc'=>'दार्मा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>562,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'तुल्सीपुर उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>563,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'घोराही उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>564,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'लमही नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>565,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'बंगलाचुली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>566,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'दंगीशरण गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>567,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'गढवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>568,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'राजपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>569,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'राप्ती गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>570,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'शान्तिनगर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>571,
            'district_id'=>'56',
            'district_name_np'=>'दाङ',
            'district_name'=>'Dang',
            'mun_vdc'=>'बबई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>572,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'नेपालगंज उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>573,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'कोहलपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>574,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'नरैनापुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>575,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'राप्तीसोनारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>576,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'बैजनाथ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>577,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'खजुरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>578,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'डुडुवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>579,
            'district_id'=>'57',
            'district_name_np'=>'बाँके',
            'district_name'=>'Bake',
            'mun_vdc'=>'जानकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>580,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'गुलरिया नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>581,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'मधुवन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>582,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'राजापुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>583,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'ठाकुरबाबा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>584,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'बाँसगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>585,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'बारबर्दिया नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>586,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'बढैयाताल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>587,
            'district_id'=>'58',
            'district_name_np'=>'बर्दिया',
            'district_name'=>'Bardiya',
            'mun_vdc'=>'गेरुवा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>588,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'बीरेन्द्रनगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>589,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'भेरीगंगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>590,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'गुर्भाकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>591,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'पञ्चपुरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>592,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'लेकवेशी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>593,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'चौकुने गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>594,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'बराहताल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>595,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'चिङ्गाड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>596,
            'district_id'=>'59',
            'district_name_np'=>'सुर्खेत',
            'district_name'=>'Sukhet',
            'mun_vdc'=>'सिम्ता गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>597,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'नारायण नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>598,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'दुल्लु नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>599,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'चामुण्डा विन्द्रासैनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>600,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'आठबीस नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>601,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'भगवतीमाई गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>602,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'गुराँस गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>603,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'डुंगेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>604,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'नौमुले गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>605,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'महावु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>606,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'भैरवी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>607,
            'district_id'=>'60',
            'district_name_np'=>'दैलेख',
            'district_name'=>'Dailekh',
            'mun_vdc'=>'ठाँटीकाँध गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>608,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'भेरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>609,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'छेडागाड नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>610,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'त्रिवेणी नलगाड नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>611,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'बारेकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>612,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'कुसे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>613,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'जुनीचाँदे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>614,
            'district_id'=>'61',
            'district_name_np'=>'जाजरकोट',
            'district_name'=>'Jajarkoat',
            'mun_vdc'=>'शिवालय गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>615,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'ठुली भेरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>616,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'त्रिपुरासुन्दरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>617,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'डोल्पो बुद्ध गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>618,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'शे फोक्सुन्डो गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>619,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'जगदुल्ला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>620,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'मुड्केचुला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>621,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'काईके गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>622,
            'district_id'=>'62',
            'district_name_np'=>'डोल्पा',
            'district_name'=>'Dolpa',
            'mun_vdc'=>'छार्का ताङसोङ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>623,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'चन्दननाथ नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>624,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'कनकासुन्दरी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>625,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'सिंजा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>626,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'हिमा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>627,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'तिला गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>628,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'गुठिचौर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>629,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'तातोपानी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>630,
            'district_id'=>'63',
            'district_name_np'=>'जुम्ला',
            'district_name'=>'Jumla',
            'mun_vdc'=>'पातारासी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>631,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'खाँडाचक्र नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>632,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'रास्कोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>633,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'तिलागुफा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>634,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'पचालझरना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>635,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'सान्नी त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>636,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'नरहरिनाथ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>637,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'कालिका गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>638,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'महावै गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>639,
            'district_id'=>'64',
            'district_name_np'=>'कालिकोट',
            'district_name'=>'kalikot',
            'mun_vdc'=>'पलाता गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>640,
            'district_id'=>'65',
            'district_name_np'=>'मुगु',
            'district_name'=>'Mugu',
            'mun_vdc'=>'छायाँनाथ रारा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>641,
            'district_id'=>'65',
            'district_name_np'=>'मुगु',
            'district_name'=>'Mugu',
            'mun_vdc'=>'मुगुम कार्मारोंग गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>642,
            'district_id'=>'65',
            'district_name_np'=>'मुगु',
            'district_name'=>'Mugu',
            'mun_vdc'=>'सोरु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>643,
            'district_id'=>'65',
            'district_name_np'=>'मुगु',
            'district_name'=>'Mugu',
            'mun_vdc'=>'खत्याड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>644,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'सिमकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>645,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'नाम्खा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>646,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'खार्पुनाथ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>647,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'सर्केगाड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>648,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'चंखेली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>649,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'अदानचुली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>650,
            'district_id'=>'66',
            'district_name_np'=>'हुम्ला',
            'district_name'=>'Humla',
            'mun_vdc'=>'ताँजाकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'6'
        ] );



        District::create( [
            'id'=>651,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'बडीमालिका नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>652,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'त्रिवेणी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>653,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'बुढीगंगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>654,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'बुढीनन्दा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>655,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'गौमुल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>656,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'पाण्डव गुफा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>657,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'स्वामीकार्तिक गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>658,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'छेडेदह गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>659,
            'district_id'=>'67',
            'district_name_np'=>'बाजुरा',
            'district_name'=>'Bajura',
            'mun_vdc'=>'हिमाली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>660,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'जयपृथ्वी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>661,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'बुंगल नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>662,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'तलकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>663,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'मष्टा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>664,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'खप्तडछान्ना गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>665,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'थलारा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>666,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'वित्थडचिर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>667,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'सूर्मा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>668,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'छबिसपाथिभेरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>669,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'दुर्गाथली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>670,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'केदारस्युँ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>671,
            'district_id'=>'68',
            'district_name_np'=>'बझाङ',
            'district_name'=>'Bajhang',
            'mun_vdc'=>'काँडा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>672,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'मंगलसेन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>673,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'कमलबजार नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>674,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'साँफेबगर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>675,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'पन्चदेवल विनायक नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>676,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'चौरपाटी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>677,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'मेल्लेख गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>678,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'बान्निगढी जयगढ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>679,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'रामारोशन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>680,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'ढकारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>681,
            'district_id'=>'69',
            'district_name_np'=>'अछाम',
            'district_name'=>'Acham',
            'mun_vdc'=>'तुर्माखाँद गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>682,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'दिपायल सिलगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>683,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'शिखर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>684,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'पूर्वीचौकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>685,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'बडीकेदार गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>686,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'जोरायल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>687,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'सायल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>688,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'आदर्श गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>689,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'के.आई.सिं. गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>690,
            'district_id'=>'70',
            'district_name_np'=>'डोटी',
            'district_name'=>'Doti',
            'mun_vdc'=>'बोगटान गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>691,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'धनगढी उपमहानगरपालिका',
            'type'=>'उपमहानगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>692,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'टिकापुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>693,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'घोडाघोडी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>694,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'लम्कीचुहा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>695,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'भजनी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>696,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'गोदावरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>697,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'गौरीगंगा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>698,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'जानकी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>699,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'बर्दगोरिया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>700,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'मोहन्याल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>701,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'कैलारी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>702,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'जोशीपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>703,
            'district_id'=>'71',
            'district_name_np'=>'कैलाली',
            'district_name'=>'Kailali',
            'mun_vdc'=>'चुरे गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>704,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'भीमदत्त नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>705,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'पुर्नवास नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>706,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'वेदकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>707,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'महाकाली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>708,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'शुक्लाफाँटा नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>709,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'बेलौरी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>710,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'कृष्णपुर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>711,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'बेलडाडी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>712,
            'district_id'=>'72',
            'district_name_np'=>'कञ्चनपुर',
            'district_name'=>'Kanchanpur',
            'mun_vdc'=>'लालझाडी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>713,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'अमरगढी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>714,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'परशुराम नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>715,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'आलिताल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>716,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'भागेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>717,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'नवदुर्गा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>718,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'अजयमेरु गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>719,
            'district_id'=>'73',
            'district_name_np'=>'डडेलधुरा',
            'district_name'=>'Dadeldhura',
            'mun_vdc'=>'गन्यापधुरा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>720,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'दशरथचन्द नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>721,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'पाटन नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>722,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'मेलौली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>723,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'पुर्चौडी नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>724,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'सुर्नया गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>725,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'सिगास गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>726,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'शिवनाथ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>727,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'पञ्चेश्वर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>728,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'दोगडाकेदार गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>729,
            'district_id'=>'74',
            'district_name_np'=>'बैतडी',
            'district_name'=>'Baitadi',
            'mun_vdc'=>'डीलासैनी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>730,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'महाकाली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>731,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'शैल्यशिखर नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>732,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'मालिकार्जुन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>733,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'अपिहिमाल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>734,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'दुहुँ गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>735,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'नौगाड गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>736,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'मार्मा गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>737,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'लेकम गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>738,
            'district_id'=>'75',
            'district_name_np'=>'दार्चुला',
            'district_name'=>'Darchula',
            'mun_vdc'=>'ब्याँस गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'7'
        ] );



        District::create( [
            'id'=>739,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'कावासोती नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>740,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'गैडाकोट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>741,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'देवचुली नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>742,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'मध्यविन्दु नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>743,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'बुङ्दीकाली गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>744,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'बुलिङटार गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>745,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'विनयी त्रिवेणी गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>746,
            'district_id'=>'76',
            'district_name_np'=>'नवलपरासी (पूर्व)',
            'district_name'=>'Nawalparasi (East)',
            'mun_vdc'=>'हुप्सेकोट गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'4'
        ] );



        District::create( [
            'id'=>747,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'बर्दघाट नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>748,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'रामग्राम नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>749,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'सुनवल नगरपालिका',
            'type'=>'नगरपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>750,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'सुस्ता गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>751,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'पाल्हीनन्दन गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>752,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'प्रतापपुर गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );



        District::create( [
            'id'=>753,
            'district_id'=>'77',
            'district_name_np'=>'नवलपरासी (पश्चिम)',
            'district_name'=>'Nawalparasi (West)',
            'mun_vdc'=>'सरावल गाउँपालिका',
            'type'=>'गाउँपालिका',
            'province'=>'5'
        ] );
    }
}
