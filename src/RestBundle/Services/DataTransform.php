<?php

namespace RestBundle\Services;

use Doctrine\ORM\EntityManager;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Page;
use RestBundle\Model\AboutUsModel;
use RestBundle\Model\CareerAdviceModel;
use RestBundle\Model\ContactUsModel;
use RestBundle\Model\FaqModel;
use RestBundle\Model\HomePageModel;
use RestBundle\Model\ServiceModel;
use RestBundle\Model\TermsOfUseModel;
use RestBundle\Model\TestimonialsModel;

/**
 * Class DataTransform
 * @package RestBundle\Services
 */
class DataTransform
{
    /** @var EntityManager $em */
    protected $em;

    /**
     * DataTransform constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Page $page
     * @return null|AboutUsModel|ContactUsModel|FaqModel|HomePageModel|ServiceModel|TermsOfUseModel|TestimonialsModel|CareerAdviceModel|BlogModel
     */
    public function transform(Page $page)
    {
        $vm = null;

        switch ($page->getSlug()) {
            case 'contact-us':
                $vm = $this->createContactUsVM($page);
                break;

            case 'faq':
                $vm = $this->createFaqVM($page);
                break;

            case 'terms-of-use':
                $vm = $this->createTermsOfUseVM($page);
                break;

            case 'about-us':
                $vm = $this->createAboutUsVM($page);
                break;

            case 'find-profession-best-career-advice-career-finder':
                $vm = $this->createHomeVM($page);
                break;

            case 'career-finder':
            case 'resume-makeover':
            case 'cover-letter-service':
            case 'linkedin-profile-makeover':
            case 'job-interview-prep':
                $vm = $this->createServiceVM($page);
                break;

            case 'testimonials':
                $vm = $this->createTestimonialsVM($page);
                break;

            case 'career-advice':
            case 'linkedin':
            case 'resume':
            case 'interviewing':
            case 'job-search':
                $vm = $this->createCareerAdviceVM($page);
                break;
        }

        return $vm;
    }

    /**
     * @param Page $page
     * @return ContactUsModel
     */
    private function createContactUsVM(Page $page)
    {
        $content = $page->getContent();
        $page_vm = new ContactUsModel();
        $matches = $this->parse($content, 'h1', null, null, true);
        $page_vm->setUrl($page->getSlug());
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());
        $page_vm->setFormTitle(strip_tags($matches[1][0]));
         
        $page_vm->setFormContent($this->parse($content, 'div', 'id', 'form_content'));
        $page_vm->setPhoneTime($this->parse($content, 'div', 'id', 'call_time'));

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return FaqModel
     */
    private function createFaqVM(Page $page)
    {
        $content = $page->getContent();
        $page_vm = new FaqModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());
        $matches = $this->parse($content);

        $data['page_title'] = $matches;

        $datas = explode('<h2>', $content);

        unset($datas[0]);

        foreach ($datas as $sections) {
            $section = explode('</h2>', $sections);
            $data['page_content'][] = ['question' => $section[0], 'answer' => strip_tags(trim($section[1]))];
        }

        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return HomePageModel
     */
    private function createHomeVM(Page $page)
    {
        $templates = [
            ['tag' => 'div', 'id' => 'page_header_title'],
            ['tag' => 'div', 'id' => 'page_header_description'],
            ['tag' => 'div', 'id' => 'slider_header_title'],
            ['tag' => 'div', 'id' => 'slider_header_description'],
            ['tag' => 'div', 'id' => 'slider_cost'],
            ['tag' => 'div', 'id' => 'packages_title'],
            ['tag' => 'div', 'id' => 'packages_list'],
            ['tag' => 'div', 'id' => 'slider_footer_text'],
            ['tag' => 'div', 'id' => 'image_header_title'],
            ['tag' => 'div', 'id' => 'image_header_description'],
            ['tag' => 'div', 'id' => 'image_list'],
            ['tag' => 'div', 'id' => 'opportunities_title'],
            ['tag' => 'div', 'id' => 'opportunities_description'],
            ['tag' => 'div', 'id' => 'calendly_title'],
            ['tag' => 'div', 'id' => 'calendly_description'],
            ['tag' => 'div', 'id' => 'testimonials_title'],
            ['tag' => 'div', 'id' => 'testimonials_description'],
            ['tag' => 'div', 'id' => 'testimonials'],
            ['tag' => 'div', 'id' => 'other_services_title'],
            ['tag' => 'div', 'id' => 'other_services_description'],
            ['tag' => 'div', 'id' => 'other_services']
        ];

        $content = $page->getContent();
        $page_vm = new HomePageModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());
        $data = [];

        $service = $this->em->getRepository('RestBundle:Service')->findOneBy(['price_executive' => null]);

        foreach ($templates as $template) {
            $result = $this->parse($content, $template['tag'], 'id', $template['id']);

            if (!is_array($result)) {
                $result = strip_tags($result);
            }

            switch ($template['id']) {
                case 'packages_list':
                    $result = array_values(array_filter(explode("\r".PHP_EOL, $result)));
                    break;

                case 'image_list':
                    $list = array_values(array_filter(explode("\r".PHP_EOL, $result)));

                    $result = [];

                    foreach ($list as $key => $value) {
                        if (!($key & 1) && $key + 1 < count($list)) {
                            $image = str_replace( ' ', '-', strtolower($value));
                            $result[] = ['image' => $image, 'title' => $value, 'description' => $list[$key + 1]];
                        } else {
                            continue;
                        }
                    }

                    break;

                // case 'slider_header_description':
                    
                //     $data_t = explode('%', $result);
                //     $result = substr($data_t[0], 0, -1) . $service->getPriceSenior() . '%' ;

                //     break;

                case 'slider_cost':
                    $result = $service->getPriceSenior();

                    break;

                case 'testimonials':
                    $testimonials = $this->em->getRepository('RestBundle:Testimonial')->getLastTestimonials();
                    $result = $testimonials;

                    break;

                case 'other_services':
                    $result = $this->em->getRepository('RestBundle:Service')->getOtherServices($service->getId());

                    break;
            }

            $data[$template['id']] = $result;
        }

        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return TermsOfUseModel
     */
    private function createTermsOfUseVM(Page $page)
    {
        $content = $page->getContent();

        $page_vm = new TermsOfUseModel();
        $matches = $this->parse($content, 'h1', null, null, true);
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());

        $data['terms_of_use']['title'] = $matches[0];
        $data['privacy_policy']['title'] = $matches[1];

        $datas = explode('</h1>', $content);
        $temp = [];

        foreach ($datas as $value) {
            $temp[] = explode('<h1>', $value)[0];
        }

        $data['terms_of_use']['content'] = $temp[1];
        $data['privacy_policy']['content'] = $temp[2];
        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return AboutUsModel
     */
    private function createAboutUsVM(Page $page)
    {
        $page_vm = new AboutUsModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());

        $templates = [
            ['tag' => 'div', 'id' => 'header'],
            ['tag' => 'div', 'id' => 'body']
        ];

        $content = $page->getContent();

        foreach ($templates as $template) {
            $result = $this->parse($content, $template['tag'], 'id', $template['id']);

            switch ($template['id']) {
                case 'body':
                    $result = implode(' ', array_values(array_filter(explode("\r".PHP_EOL, $result))));
                    break;
            }

            $data[$template['id']] = $result;
        }

        $page_vm->setContent($data);
        $page_vm->setImage($page->getImage());

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return ServiceModel
     */
    private function createServiceVM(Page $page)
    {
        $templates = [
            ['tag' => 'div', 'id' => 'sub_title'],
            ['tag' => 'div', 'id' => 'steps'],
            ['tag' => 'div', 'id' => 'body'],
            ['tag' => 'div', 'id' => 'slider_header_title'],
            ['tag' => 'div', 'id' => 'packages'],
            ['tag' => 'div', 'id' => 'testimonials_title'],
            ['tag' => 'div', 'id' => 'other_services_title']
        ];

        $page_vm = new ServiceModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());
        $page_vm->setLink($page->getSlug());

        $content = $page->getContent();

        $service = $this->em->getRepository('RestBundle:Service')->findOneBy(['link' => $page->getSlug()]);

        $data = [];

        foreach ($templates as $template) {
            $result = $this->parse($content, $template['tag'], 'id', $template['id']);

            switch ($template['id']) {
                case 'sub_title':
                    $result = array_values(array_filter(explode("\r".PHP_EOL, strip_tags($result))));
                    break;

                case 'body':
                    $result = implode(' ', array_values(array_filter(explode("\r".PHP_EOL, $result))));
                    break;

                case 'steps':
                    $list = array_values(array_filter(explode("\r".PHP_EOL, strip_tags($result))));

                    $result = [];

                    foreach ($list as $key => $value) {
                        if (!($key & 1) && $key + 1 < count($list)) {
                            $result[] = ['step' => $value, 'description' => $list[$key + 1]];
                        } else {
                            continue;
                        }
                    }

                    break;

                case 'packages':
                    if (empty($result)) {
                        $result = null;
                        break;
                    }

                    $titles = $this->parse($result, 'p', null, null, true);
                    $list = $this->parse($result, 'ul', null, null, true);
                    $array = [];

                    foreach ($list[1] as $value) {
                        $array[] = $this->parse($value, 'li', null, null, true);
                    }

                    $result = [];

                    $result['id'] = $service->getId();

                    $result['senior'] = [
                        'title' => $titles[1][0],
                        'description' => $titles[1][1],
                        'list' => $array[0][1],
                        'price' => $service->getPriceSenior()
                    ];

                    $result['executive'] = [
                        'title' => $titles[1][2],
                        'description' => $titles[1][3],
                        'list' => $array[1][1],
                        'price' => $service->getPriceExecutive()
                    ];

                    break;

                case 'other_services':
                    $services = $this->em->getRepository('RestBundle:Service')->getOtherServices($service->getId());
                    $result = $services;

                    break;
            }

            $data[$template['id']] = $result;
        }

        if (null === $service->getPriceExecutive()) {
            $home_page = $this->em->getRepository('RestBundle:Page')
                ->findOneBy(['slug' => 'find-profession-best-career-advice-career-finder']);

            $home_page_vm = $this->createHomeVM($home_page)->getContent();

            $data['slider'] = [
                'slider_header_title' => strip_tags($data['slider_header_title']),
                'slider_header_description' => strip_tags($home_page_vm['slider_header_description']),
                'slider_cost' => $service->getPriceSenior(),
                'packages_title' => $home_page_vm['packages_title'],
                'packages_list' => $home_page_vm['packages_list']
            ];
        }

        unset($data['slider_header_title']);

        $data['testimonials'] = $this->em->getRepository('RestBundle:Testimonial')
            ->getLastTestimonialsForService($service);

        $data['services'] = $this->em->getRepository('RestBundle:Service')
            ->getOtherServices($service->getId(), true);

        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return TestimonialsModel
     */
    private function createTestimonialsVM(Page $page)
    {
        $page_vm = new TestimonialsModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());

        $templates = [
            ['tag' => 'div', 'id' => 'title'],
            ['tag' => 'div', 'id' => 'sub_title']
        ];

        $content = $page->getContent();
        $data = [];

        foreach ($templates as $template) {
            $result = $this->parse($content, $template['tag'], 'id', $template['id']);
            $data[$template['id']] = strip_tags($result);
        }

        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param Page $page
     * @return CareerAdviceModel
     */
    private function createCareerAdviceVM(Page $page)
    {
        
        $page_vm = new CareerAdviceModel();
        $page_vm->setTitle($page->getTitle());
        $page_vm->setSeoTitle($page->getSeoTitle());
        $page_vm->setDescription($page->getDescription());

        $templates = [
            ['tag' => 'div', 'id' => 'title'],
            ['tag' => 'div', 'id' => 'sub_title'],
            ['tag' => 'div', 'id' => 'description']
        ];

        $content = $page->getContent();
        $data = [];

        foreach ($templates as $template) {
            $result = $this->parse($content, $template['tag'], 'id', $template['id']);
            if ($template['id'] === 'title') {
                $result = strip_tags($result);
            }

            $data[$template['id']] = $result;
        }

        $page_vm->setContent($data);

        return $page_vm;
    }

    /**
     * @param string $string
     * @param string $tag
     * @param string $type
     * @param null $value
     * @param bool $all
     * @return string
     */
    private function parse($string, $tag = 'h1', $type = 'id', $value = null, $all = false)
    {
        $pattern = "/\<$tag>(.*?)\<\/$tag>/is";

        if ($value) {
            $pattern = "/\<$tag $type=\"$value\">(.*?)\<\/$tag>/is";
        }

        if ($all) {
            preg_match_all($pattern, $string, $data);
        } else {
            preg_match($pattern, $string, $data);
        }

        return !empty($data) && isset($data[1]) && !is_array($data[1]) ? trim($data[1]) : $data;
    }
}