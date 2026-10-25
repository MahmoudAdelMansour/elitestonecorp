<?php

namespace Database\Seeders;

use App\Enums\PartnerType;
use App\Enums\ServiceGroup;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Initial website content taken from the Elite Stone company profile (2025).
 * Everything here is editable afterwards from the admin panel.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $sectors = $this->seedSectors();
        $services = $this->seedServices();
        $partners = $this->seedPartners();
        $this->seedProjects($sectors, $services, $partners);
    }

    private function asset(string $source, string $folder): string
    {
        $path = $folder.'/'.basename($source);
        Storage::disk('public')->put($path, file_get_contents(database_path('seeders/assets/'.$source)));

        return $path;
    }

    private function seedSettings(): void
    {
        Setting::put([
            'phone' => '+20 120 899 4890',
            'whatsapp' => '201208994890',
            'email' => 'info@elitestone-corp.com',
            'address_en' => 'Cairo, Egypt',
            'address_ar' => 'القاهرة، مصر',
            'working_hours_en' => 'Saturday – Thursday, 9:00 AM – 6:00 PM',
            'working_hours_ar' => 'السبت – الخميس، 9:00 ص – 6:00 م',
            'map_query' => 'Cairo, Egypt',
            'facebook' => null,
            'instagram' => 'https://www.instagram.com/elitestone2024',
            'linkedin' => null,
            'founded_year' => 2019,
            'hero_image' => $this->asset('photos/interior-lounge.webp', 'site'),
            'about_image' => $this->asset('photos/blueprints-hardhat.webp', 'site'),
            'profile_pdf' => $this->asset('elite-stone-profile-2025.pdf', 'site'),
            'notification_email' => 'info@elitestone-corp.com',
        ]);
    }

    /**
     * @return array<string, Sector>
     */
    private function seedSectors(): array
    {
        $rows = [
            ['industrial', 'factory', 'epoxy-industrial-blue', 'Industrial & manufacturing', 'المصانع والصناعة',
                'Heavy-duty epoxy, PU and cementitious floors for production halls, hangars, warehouses and parking areas.',
                'أرضيات إيبوكسي وبولي يوريثان وأسمنتية شديدة التحمل لصالات الإنتاج والهناجر والمخازن والجراجات.'],
            ['food-beverage', 'wheat', 'hygienic-production-floor', 'Food & beverage', 'الأغذية والمشروبات',
                'Seamless, slip-resistant, easy-to-clean floors that support food-safety audits and heavy wash-downs.',
                'أرضيات متصلة مانعة للانزلاق وسهلة التنظيف تدعم اشتراطات سلامة الغذاء والغسيل المستمر.'],
            ['healthcare', 'hospital', 'corridor-terrazzo', 'Healthcare & laboratories', 'الرعاية الصحية والمعامل',
                'Hygienic, non-porous systems and complete fit-out for hospitals, clinics and laboratories.',
                'أنظمة صحية غير مسامية وتشطيبات متكاملة للمستشفيات والعيادات والمعامل.'],
            ['hospitality', 'concierge-bell', 'restaurant-cave-interior', 'Hospitality & leisure', 'الضيافة والترفيه',
                'Decorative floors, wet-area waterproofing and finishing for hotels, restaurants, gyms and leisure venues.',
                'أرضيات ديكورية وعزل للمناطق الرطبة وتشطيبات للفنادق والمطاعم والصالات الرياضية وأماكن الترفيه.'],
            ['education', 'graduation-cap', 'drawings-review', 'Education & institutions', 'التعليم والمؤسسات',
                'Durable floors, parking areas and technical rooms for universities and public institutions.',
                'أرضيات متينة وجراجات وغرف فنية للجامعات والمؤسسات العامة.'],
            ['commercial', 'store', 'epoxy-metallic', 'Commercial & retail', 'التجاري والتجزئة',
                'Showrooms, salons and offices with floors and finishes that express the brand.',
                'صالات عرض وصالونات ومكاتب بأرضيات وتشطيبات تعبر عن العلامة التجارية.'],
        ];

        $sectors = [];
        foreach ($rows as $i => [$slug, $icon, $image, $en, $ar, $descEn, $descAr]) {
            $sectors[$slug] = Sector::query()->updateOrCreate(['slug' => $slug], [
                'name_en' => $en,
                'name_ar' => $ar,
                'description_en' => $descEn,
                'description_ar' => $descAr,
                'icon' => $icon,
                'image' => $this->asset("photos/{$image}.webp", 'sectors'),
                'sort_order' => $i,
            ]);
        }

        return $sectors;
    }

    /**
     * @return array<string, Service>
     */
    private function seedServices(): array
    {
        $rows = [
            [
                'slug' => 'industrial-flooring', 'group' => ServiceGroup::Systems, 'icon' => 'factory', 'image' => 'epoxy-industrial-blue',
                'title_en' => 'Industrial flooring', 'title_ar' => 'الأرضيات الصناعية',
                'excerpt_en' => 'Robust epoxy, PU and cementitious self-levelling floors built for heavy traffic, impact and chemicals.',
                'excerpt_ar' => 'أرضيات إيبوكسي وبولي يوريثان وأسمنتية ذاتية التسوية تتحمل الحركة الثقيلة والصدمات والمواد الكيميائية.',
                'body_en' => "We specialise in robust, high-performance flooring systems tailored to industrial environments. With extensive expertise in concrete, cementitious self-levelling and epoxy systems, we understand the demands of heavy-duty operations.\n\nWe assess each area’s specific needs and select the right system for wear resistance, chemical resistance and long-term performance — so your floors last and perform even under the toughest conditions.",
                'body_ar' => "نتخصص في أنظمة الأرضيات القوية عالية الأداء المصممة خصيصًا للبيئات الصناعية. وبفضل خبرتنا الواسعة في الخرسانة والطبقات الأسمنتية ذاتية التسوية وأنظمة الإيبوكسي، نفهم متطلبات التشغيل الشاق.\n\nنقيّم احتياجات كل مساحة ونختار النظام المناسب لمقاومة التآكل والمواد الكيميائية والأداء طويل المدى، لتدوم أرضياتك وتؤدي دورها حتى في أصعب الظروف.",
                'features_en' => "Epoxy coatings and self-levelling systems\nPolyurethane and PU-cement floors\nCementitious self-levelling screeds\nAnti-static (ESD) floors\nParking deck and car park systems\nLine marking and safety zoning",
                'features_ar' => "دهانات إيبوكسي وأنظمة ذاتية التسوية\nأرضيات بولي يوريثان وبولي يوريثان أسمنتي\nطبقات أسمنتية ذاتية التسوية\nأرضيات مضادة للكهرباء الساكنة\nأنظمة الجراجات وأسطح الانتظار\nتخطيط الممرات ومناطق السلامة",
            ],
            [
                'slug' => 'decorative-flooring', 'group' => ServiceGroup::Systems, 'icon' => 'gem', 'image' => 'epoxy-metallic',
                'title_en' => 'Decorative flooring', 'title_ar' => 'الأرضيات الديكورية',
                'excerpt_en' => 'Epoxy terrazzo, metallic epoxy, cementitious terrazzo and micro cement — striking surfaces engineered to last.',
                'excerpt_ar' => 'تيرازو إيبوكسي، وإيبوكسي ميتاليك، وتيرازو أسمنتي، وميكرو سيمنت؛ أسطح لافتة مصممة لتدوم.',
                'body_en' => "We specialise in high-end decorative flooring systems that combine stunning aesthetics with unmatched durability. From epoxy terrazzo and metallic epoxy to cementitious terrazzo and micro cement, every floor is visually striking and engineered to last.\n\nWe take great pride in meticulous finishing, paying attention to every detail to deliver a flawless surface that enhances the beauty of your space and stands up to daily use.",
                'body_ar' => "نتخصص في أنظمة الأرضيات الديكورية الراقية التي تجمع بين الجمال اللافت والمتانة الاستثنائية. من التيرازو الإيبوكسي والإيبوكسي الميتاليك إلى التيرازو الأسمنتي والميكرو سيمنت، كل أرضية مميزة بصريًا ومصممة لتدوم.\n\nنفخر بدقة التشطيب والاهتمام بكل تفصيلة لنقدم سطحًا خاليًا من العيوب يبرز جمال مساحتك ويتحمل الاستخدام اليومي.",
                'features_en' => "Epoxy terrazzo\nMetallic epoxy\nCementitious terrazzo\nMicro cement for floors and walls\nCustom patterns, logos and inlays",
                'features_ar' => "تيرازو إيبوكسي\nإيبوكسي ميتاليك\nتيرازو أسمنتي\nميكرو سيمنت للأرضيات والحوائط\nتصميمات وشعارات وتطعيمات حسب الطلب",
            ],
            [
                'slug' => 'hygienic-flooring', 'group' => ServiceGroup::Systems, 'icon' => 'shield-check', 'image' => 'hygienic-production-floor',
                'title_en' => 'Hygienic flooring', 'title_ar' => 'الأرضيات الصحية',
                'excerpt_en' => 'Seamless, non-porous, easy-to-clean floors for hospitals, pharmaceutical plants and food & beverage facilities.',
                'excerpt_ar' => 'أرضيات متصلة غير مسامية وسهلة التنظيف للمستشفيات ومصانع الأدوية ومنشآت الأغذية والمشروبات.',
                'body_en' => "We understand the critical importance of hygiene in sensitive environments such as hospitals, medical factories and food & beverage facilities. We select and apply flooring systems that meet and exceed health and safety standards.\n\nEvery solution is seamless, non-porous and easy to clean — preventing the build-up of contaminants and supporting strict sanitation protocols, so your space stays compliant and protected.",
                'body_ar' => "ندرك الأهمية القصوى للنظافة في البيئات الحساسة مثل المستشفيات ومصانع الأدوية ومنشآت الأغذية والمشروبات. لذلك نختار وننفذ أنظمة أرضيات تلبي معايير الصحة والسلامة وتتجاوزها.\n\nكل حل نقدمه متصل وغير مسامي وسهل التنظيف، يمنع تراكم الملوثات ويدعم بروتوكولات التعقيم الصارمة لتبقى منشأتك مطابقة للاشتراطات ومحمية.",
                'features_en' => "Seamless PU-cement and epoxy systems\nCoved skirtings and sealed joints\nSlip-resistant textures for wet areas\nChemical and thermal-shock resistance\nClean-room and laboratory floors",
                'features_ar' => "أنظمة بولي يوريثان أسمنتي وإيبوكسي متصلة\nوزرات منحنية وفواصل محكمة الغلق\nأسطح مانعة للانزلاق للمناطق الرطبة\nمقاومة للمواد الكيميائية والصدمات الحرارية\nأرضيات الغرف النظيفة والمعامل",
            ],
            [
                'slug' => 'concrete-repair', 'group' => ServiceGroup::Systems, 'icon' => 'hammer', 'image' => 'concrete-repair-trowel',
                'title_en' => 'Concrete repair', 'title_ar' => 'إصلاح الخرسانة',
                'excerpt_en' => 'Restoring cracks, spalls and worn concrete with epoxy, cementitious and polymer-modified repair systems.',
                'excerpt_ar' => 'معالجة الشروخ والتقشر والخرسانة المتآكلة بأنظمة إصلاح إيبوكسية وأسمنتية ومعدلة بالبوليمر.',
                'body_en' => "We excel in concrete repair, selecting and applying the most suitable materials to restore and enhance the structural integrity of your surfaces — whether addressing cracks, spalls or general wear and tear.\n\nWe evaluate the condition of the concrete and choose from high-performance epoxy resins, cementitious mortars and polymer-modified compounds, ensuring a seamless bond and long-lasting durability at a sensible cost.",
                'body_ar' => "نتميز في إصلاح الخرسانة باختيار وتطبيق أنسب المواد لاستعادة السلامة الإنشائية للأسطح وتحسينها، سواء لمعالجة الشروخ أو التقشر أو التآكل العام.\n\nنقيّم حالة الخرسانة ونختار من راتنجات الإيبوكسي والمونة الأسمنتية والمركبات المعدلة بالبوليمر عالية الأداء، لضمان ترابط محكم ومتانة طويلة بتكلفة مناسبة.",
                'features_en' => "Condition survey and repair method statement\nCrack and spall repair\nStructural repair mortars\nEpoxy bonding and grouting\nSurface levelling and protection",
                'features_ar' => "معاينة الحالة وإعداد طريقة التنفيذ\nإصلاح الشروخ والتقشر\nمونة إصلاح إنشائية\nلصق وحقن بالإيبوكسي\nتسوية الأسطح وحمايتها",
            ],
            [
                'slug' => 'waterproofing-thermal-insulation', 'group' => ServiceGroup::Systems, 'icon' => 'droplets', 'image' => 'waterproofing-application',
                'title_en' => 'Water & thermal proofing', 'title_ar' => 'العزل المائي والحراري',
                'excerpt_en' => 'Cementitious, epoxy, polyurethane and PVC systems against positive and negative water pressure and heat.',
                'excerpt_ar' => 'أنظمة أسمنتية وإيبوكسي وبولي يوريثان وPVC ضد ضغط المياه الموجب والسالب والحرارة.',
                'body_en' => "We specialise in advanced waterproofing and thermal-proofing solutions designed to protect and enhance the integrity of your structures. With expertise in cementitious, epoxy, polyurethane and PVC materials, we select the right system for both positive and negative water pressure.\n\nWhether addressing persistent leaks, moisture intrusion or thermal inefficiency, we find solutions that work even in the most difficult situations — delivering long-lasting protection and energy efficiency.",
                'body_ar' => "نتخصص في حلول العزل المائي والحراري المتقدمة المصممة لحماية المنشآت وتحسين سلامتها. وبخبرتنا في المواد الأسمنتية والإيبوكسي والبولي يوريثان وPVC، نختار النظام المناسب لضغط المياه الموجب والسالب.\n\nسواء كانت المشكلة تسربًا مستمرًا أو تسلل الرطوبة أو ضعف العزل الحراري، نجد الحلول التي تنجح حتى في أصعب الحالات، لنقدم حماية طويلة الأمد وكفاءة في استهلاك الطاقة.",
                'features_en' => "Roofs, basements and foundations\nWet areas, pools and water tanks\nPolyurethane and PVC membranes\nCementitious crystalline systems\nThermal insulation for roofs",
                'features_ar' => "الأسطح والبدرومات والأساسات\nالمناطق الرطبة وحمامات السباحة وخزانات المياه\nأغشية بولي يوريثان وPVC\nأنظمة أسمنتية بلورية\nعزل حراري للأسطح",
            ],
            [
                'slug' => 'injection', 'group' => ServiceGroup::Systems, 'icon' => 'syringe', 'image' => 'crack-injection',
                'title_en' => 'Leak injection', 'title_ar' => 'أعمال الحقن',
                'excerpt_en' => 'Stopping water leaks in tanks, pools, expansion joints and soil with PU foam, acrylic gel and cementitious injection.',
                'excerpt_ar' => 'إيقاف تسرب المياه في الخزانات وحمامات السباحة وفواصل التمدد والتربة بالحقن بفوم البولي يوريثان والجل الأكريليكي والمواد الأسمنتية.',
                'body_en' => "We offer effective injection solutions for water leakage in tanks, pools, expansion joints and even soil. Using cementitious grouts, polyurethane foam and acrylic gel, we tailor the approach to each case.\n\nWe select the injection material based on the nature of the leak and the site conditions, creating a reliable, long-lasting seal. Injection is a cost-effective, non-disruptive way to restore the integrity of your structures.",
                'body_ar' => "نقدم حلول حقن فعالة لتسرب المياه في الخزانات وحمامات السباحة وفواصل التمدد وحتى التربة، باستخدام الحقن الأسمنتي وفوم البولي يوريثان والجل الأكريليكي حسب كل حالة.\n\nنختار مادة الحقن وفق طبيعة التسرب وظروف الموقع لنصنع عزلًا موثوقًا طويل الأمد. الحقن طريقة اقتصادية لا تعطل التشغيل وتعيد للمنشأة سلامتها.",
                'features_en' => "Polyurethane foam injection\nAcrylic gel and curtain injection\nCementitious and micro-fine grouts\nExpansion joint sealing\nSoil stabilisation",
                'features_ar' => "حقن فوم البولي يوريثان\nحقن الجل الأكريليكي والحقن الستاري\nحقن أسمنتي وميكرو أسمنتي\nعزل فواصل التمدد\nتثبيت التربة",
            ],
            [
                'slug' => 'interior-design', 'group' => ServiceGroup::Contracting, 'icon' => 'pen-tool', 'image' => 'living-render',
                'title_en' => 'Interior design', 'title_ar' => 'التصميم الداخلي',
                'excerpt_en' => '2D and 3D designs that let you visualise every simple and complex detail before execution.',
                'excerpt_ar' => 'تصميمات ثنائية وثلاثية الأبعاد تتيح لك رؤية كل التفاصيل البسيطة والمعقدة قبل التنفيذ.',
                'body_en' => "Our team includes qualified engineers and local construction partners in tune with the needs and materials of the industry.\n\nWe offer 2D and 3D designs so you can explore a wide range of options and visualise every simple and complex detail before implementation — reducing changes on site and keeping your budget on track.",
                'body_ar' => "يضم فريقنا مهندسين مؤهلين وشركاء بناء محليين على دراية باحتياجات الصناعة وخاماتها.\n\nنقدم تصميمات ثنائية وثلاثية الأبعاد لتستكشف مجموعة واسعة من الخيارات وترى كل التفاصيل البسيطة والمعقدة قبل التنفيذ، ما يقلل التعديلات في الموقع ويحافظ على الميزانية.",
                'features_en' => "Space planning and layouts\n3D visualisation\nMaterial and finish selection\nLighting and ceiling design\nShop drawings for execution",
                'features_ar' => "تخطيط المساحات\nتصور ثلاثي الأبعاد\nاختيار الخامات والتشطيبات\nتصميم الإضاءة والأسقف\nرسومات تنفيذية",
            ],
            [
                'slug' => 'finishing-fit-out', 'group' => ServiceGroup::Contracting, 'icon' => 'paint-roller', 'image' => 'corridor-terrazzo',
                'title_en' => 'Finishing & fit-out', 'title_ar' => 'التشطيبات والتجهيزات',
                'excerpt_en' => 'Complete finishing contracting: premium fit-out, electro-mechanical works and special systems.',
                'excerpt_ar' => 'مقاولات تشطيبات متكاملة: تجهيزات راقية وأعمال كهروميكانيكية وأنظمة خاصة.',
                'body_en' => "We provide complete finishing contracting services including premium fit-out works, electro-mechanical works and special systems. From concept to creation, we apply the highest engineering standards to meet and exceed our clients’ expectations.\n\nOur executors focus on the luster, texture and colour of every material, sourcing durable, high-quality products from local and international suppliers — while our management keeps schedules tight and prices reasonable.",
                'body_ar' => "نقدم خدمات مقاولات التشطيبات المتكاملة، بما في ذلك أعمال التجهيز الراقية والأعمال الكهروميكانيكية والأنظمة الخاصة. ومن الفكرة إلى التنفيذ، نطبق أعلى المعايير الهندسية لنلبي توقعات عملائنا ونتجاوزها.\n\nيهتم منفذونا بلمعة كل خامة وملمسها ولونها، ونختار منتجات متينة عالية الجودة من موردين محليين ودوليين، بينما تحافظ إدارتنا على الجداول الزمنية والأسعار المناسبة.",
                'features_en' => "Turnkey fit-out\nGypsum board, ceilings and partitions\nWall finishes and cladding\nElectro-mechanical works\nJoinery and special systems",
                'features_ar' => "تجهيز متكامل تسليم مفتاح\nجبس بورد وأسقف وقواطع\nتشطيب الحوائط والتكسيات\nأعمال كهروميكانيكية\nأعمال نجارة وأنظمة خاصة",
            ],
            [
                'slug' => 'project-management', 'group' => ServiceGroup::Contracting, 'icon' => 'clipboard-check', 'image' => 'team-planning',
                'title_en' => 'Project management', 'title_ar' => 'إدارة المشروعات',
                'excerpt_en' => 'Disciplined initiation, planning, execution, monitoring and close-out to deliver on time and to specification.',
                'excerpt_ar' => 'إدارة منضبطة من البدء والتخطيط والتنفيذ والمتابعة حتى الإغلاق لتسليم في الموعد وبالمواصفات.',
                'body_en' => "We maintain discipline in initiating, planning, executing, monitoring and closing every project, to achieve the best quality and meet strict criteria that guarantee success within the specified time.\n\nA single point of contact coordinates design, procurement, site crews and quality control, with clear reporting to your team at every stage.",
                'body_ar' => "نلتزم بالانضباط في بدء كل مشروع وتخطيطه وتنفيذه ومتابعته وإغلاقه، لتحقيق أفضل جودة واستيفاء معايير صارمة تضمن النجاح في الوقت المحدد.\n\nمسؤول تواصل واحد ينسق التصميم والمشتريات وفرق الموقع وضبط الجودة، مع تقارير واضحة لفريقك في كل مرحلة.",
                'features_en' => "Planning and scheduling\nProcurement and supplier coordination\nSite supervision and HSE\nQuality control and testing\nProgress reporting and handover",
                'features_ar' => "التخطيط والجدولة\nالمشتريات والتنسيق مع الموردين\nالإشراف على الموقع والسلامة\nضبط الجودة والاختبارات\nتقارير التقدم والتسليم",
            ],
        ];

        $services = [];
        foreach ($rows as $i => $row) {
            $row['image'] = $this->asset("photos/{$row['image']}.webp", 'services');
            $services[$row['slug']] = Service::query()->updateOrCreate(['slug' => $row['slug']], [...$row, 'sort_order' => $i]);
        }

        return $services;
    }

    /**
     * @return array<string, Partner>
     */
    private function seedPartners(): array
    {
        $rows = [
            [PartnerType::Client, 'edita', 'EDITA'],
            [PartnerType::Client, 'marriott', 'Marriott'],
            [PartnerType::Client, 'nile-ritz-carlton', 'The Nile Ritz-Carlton'],
            [PartnerType::Client, 'esca-hospitality', 'ESCA Hospitality Group'],
            [PartnerType::Client, 'elsewedy-electric', 'Elsewedy Electric'],
            [PartnerType::Client, 'sierra', 'Sierra Engineering & Manufacturing'],
            [PartnerType::Client, 'gcf', 'GCF'],
            [PartnerType::Client, 'first-cosmetics', 'First Cosmetics'],
            [PartnerType::Partner, 'basf-master-builders', 'Master Builders Solutions (BASF)'],
            [PartnerType::Partner, 'sika', 'Sika'],
            [PartnerType::Partner, 'mapei', 'Mapei'],
            [PartnerType::Partner, 'bodo-moller-chemie', 'Bodo Möller Chemie'],
            [PartnerType::Partner, 'velosit', 'Velosit'],
            [PartnerType::Partner, 'hemts', 'HEMTS Construction Chemicals'],
            [PartnerType::Partner, 'scib-paints', 'Scib Paints'],
        ];

        $partners = [];
        foreach ($rows as $i => [$type, $file, $name]) {
            $partners[$file] = Partner::query()->updateOrCreate(['name' => $name], [
                'type' => $type,
                'logo' => $this->asset("logos/{$file}.png", 'partners'),
                'sort_order' => $i,
            ]);
        }

        return $partners;
    }

    /**
     * @param  array<string, Sector>  $sectors
     * @param  array<string, Service>  $services
     * @param  array<string, Partner>  $partners
     */
    private function seedProjects(array $sectors, array $services, array $partners): void
    {
        // [year, title_en, title_ar, sector, services, client, cover, location_en, location_ar, featured]
        $rows = [
            [2025, 'ESCA Playa Restaurant – El Alamein', 'مطعم إسكا بلايا – العلمين', 'hospitality', ['decorative-flooring', 'finishing-fit-out'], 'esca-hospitality', 'exterior-walkway', 'El Alamein, North Coast', 'العلمين، الساحل الشمالي', false],
            [2025, 'Fathy Mahmoud Showroom Flooring', 'أرضيات معرض فتحي محمود', 'commercial', ['decorative-flooring'], null, 'epoxy-metallic', 'Cairo', 'القاهرة', false],
            [2025, 'MUST University Parking Area', 'جراج جامعة مصر للعلوم والتكنولوجيا', 'education', ['industrial-flooring'], null, 'drawings-review', '6th of October City', 'مدينة 6 أكتوبر', false],
            [2025, 'KRISS Beauty Salon Flooring', 'أرضيات صالون كريس للتجميل', 'commercial', ['decorative-flooring'], null, 'bright-living-room', 'Cairo', 'القاهرة', false],
            [2025, 'ESCA Terrenal Maintenance', 'أعمال صيانة مطعم إسكا تيرينال', 'hospitality', ['finishing-fit-out', 'concrete-repair'], 'esca-hospitality', 'garden-path-night', 'Cairo', 'القاهرة', false],
            [2025, 'EDITA Warehouse Concrete Repair', 'إصلاح خرسانة مخزن إيديتا', 'food-beverage', ['concrete-repair'], 'edita', 'concrete-repair-trowel', 'Greater Cairo', 'القاهرة الكبرى', true],
            [2024, 'ESCA Cueva Restaurant Flooring', 'أرضيات مطعم إسكا كويفا', 'hospitality', ['decorative-flooring'], 'esca-hospitality', 'restaurant-cave-interior', 'Cairo', 'القاهرة', true],
            [2024, 'Marriott Omar Khayyam – Moroccan Baths', 'فندق ماريوت عمر الخيام – الحمامات المغربية', 'hospitality', ['waterproofing-thermal-insulation', 'finishing-fit-out'], 'marriott', 'corridor-terrazzo', 'Zamalek, Cairo', 'الزمالك، القاهرة', true],
            [2024, 'Nile Ritz-Carlton Gym', 'صالة رياضية – نايل ريتز كارلتون', 'hospitality', ['decorative-flooring'], 'nile-ritz-carlton', 'duplex-living', 'Downtown Cairo', 'وسط القاهرة', false],
            [2024, 'EDITA Foods Warehouses Flooring', 'أرضيات مخازن إيديتا للأغذية', 'food-beverage', ['industrial-flooring'], 'edita', 'epoxy-industrial-blue', 'Greater Cairo', 'القاهرة الكبرى', true],
            [2024, 'Nile Ritz-Carlton Suites', 'أجنحة نايل ريتز كارلتون', 'hospitality', ['finishing-fit-out', 'interior-design'], 'nile-ritz-carlton', 'bedroom-feature-wall', 'Downtown Cairo', 'وسط القاهرة', true],
            [2023, 'Sayed Galal Hospital Lecture Hall', 'قاعة محاضرات مستشفى سيد جلال', 'healthcare', ['finishing-fit-out'], null, 'apartment-entrance', 'Cairo', 'القاهرة', false],
            [2023, 'Ain Shams University Electrical Transformer Room', 'غرفة المحولات الكهربائية – جامعة عين شمس', 'education', ['industrial-flooring'], null, 'finishing-works', 'Cairo', 'القاهرة', false],
            [2023, 'Sierra Factory Manufacturing Hangar', 'هنجر التصنيع – مصنع سييرا', 'industrial', ['industrial-flooring'], 'sierra', 'hygienic-production-floor', 'Industrial zone, Greater Cairo', 'منطقة صناعية، القاهرة الكبرى', true],
            [2023, 'GCF Factory Production Floor', 'أرضية الإنتاج – مصنع GCF', 'industrial', ['industrial-flooring'], 'gcf', 'epoxy-industrial-blue', 'Industrial zone, Greater Cairo', 'منطقة صناعية، القاهرة الكبرى', false],
            [2023, 'GCF Factory Parking Area', 'جراج مصنع GCF', 'industrial', ['industrial-flooring'], 'gcf', 'waterproofing-application', 'Industrial zone, Greater Cairo', 'منطقة صناعية، القاهرة الكبرى', false],
            [2023, 'Dental Land Clinic Finishing', 'تشطيب عيادة دنتال لاند', 'healthcare', ['finishing-fit-out', 'interior-design'], null, 'living-render', 'Cairo', 'القاهرة', false],
            [2023, 'Jonson Factory Lab Flooring', 'أرضيات معمل مصنع جونسون', 'healthcare', ['hygienic-flooring'], null, 'hygienic-production-floor', 'Greater Cairo', 'القاهرة الكبرى', false],
            [2023, 'Egyptian German Factory Production Area Flooring', 'أرضيات منطقة الإنتاج – المصنع المصري الألماني', 'industrial', ['industrial-flooring'], null, 'epoxy-industrial-blue', 'Industrial zone, Greater Cairo', 'منطقة صناعية، القاهرة الكبرى', false],
            [2023, 'Inlane Bowling Center Finishing & Flooring', 'تشطيب وأرضيات مركز إنلين للبولينج', 'hospitality', ['decorative-flooring', 'finishing-fit-out'], null, 'duplex-living', 'Cairo', 'القاهرة', false],
            [2023, 'El Forsan Foods Factory Production Area Flooring', 'أرضيات منطقة الإنتاج – مصنع الفرسان للأغذية', 'food-beverage', ['hygienic-flooring', 'industrial-flooring'], null, 'hygienic-production-floor', 'Industrial zone, Greater Cairo', 'منطقة صناعية، القاهرة الكبرى', false],
            [2022, 'Track International Gate Finishing', 'تشطيب بوابة تراك إنترناشونال', 'commercial', ['finishing-fit-out'], null, 'entrance-door', 'Cairo', 'القاهرة', false],
            [2022, 'Rafiky Pets Clinic Finishing', 'تشطيب عيادة رفيقي للحيوانات الأليفة', 'healthcare', ['finishing-fit-out', 'interior-design'], null, 'navy-palette-bedroom', 'Cairo', 'القاهرة', false],
        ];

        foreach ($rows as $i => [$year, $en, $ar, $sector, $serviceSlugs, $client, $cover, $locEn, $locAr, $featured]) {
            $primary = $services[$serviceSlugs[0]];
            $sectorModel = $sectors[$sector];

            $project = Project::query()->updateOrCreate(['slug' => Str::slug($en)], [
                'title_en' => $en,
                'title_ar' => $ar,
                'sector_id' => $sectorModel->id,
                'client_id' => $client ? $partners[$client]->id : null,
                'year' => $year,
                'location_en' => $locEn,
                'location_ar' => $locAr,
                'summary_en' => "{$primary->title_en} works delivered by Elite Stone for a {$sectorModel->name_en} facility in {$locEn}.",
                'summary_ar' => "أعمال {$primary->title_ar} نفذتها إيليت ستون لمنشأة في قطاع {$sectorModel->name_ar} في {$locAr}.",
                'challenge_en' => "The client needed a durable, good-looking result delivered on a tight schedule, with minimal disruption to daily operations. {$sectorModel->description_en}",
                'challenge_ar' => "احتاج العميل إلى نتيجة متينة وجميلة تُسلَّم وفق جدول زمني ضيق وبأقل تأثير على التشغيل اليومي. {$sectorModel->description_ar}",
                'solution_en' => "After a site survey, our engineers specified the right system and executed it with our own crews. {$primary->excerpt_en}",
                'solution_ar' => "بعد معاينة الموقع، حدد مهندسونا النظام المناسب ونفذته فرقنا. {$primary->excerpt_ar}",
                'cover_image' => $this->asset("photos/{$cover}.webp", 'projects'),
                'is_featured' => $featured,
                'sort_order' => $i,
            ]);

            $project->services()->sync(collect($serviceSlugs)->map(fn (string $slug) => $services[$slug]->id));
        }
    }
}
