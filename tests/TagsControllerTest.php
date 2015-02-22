<?php
use TypiCMS\Modules\Tags\Models\Tag;

class TagsControllerTest extends TestCase
{

    public function testAdminIndex()
    {
        $response = $this->call('GET', 'admin/tags');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStoreFails()
    {
        $input = ['fr.title' => 'test', 'fr.slug' => ''];
        $this->call('POST', 'admin/tags', $input);
        $this->assertRedirectedToRoute('admin.tags.create');
        $this->assertSessionHasErrors();
    }

    public function testStoreSuccess()
    {
        $object = new Tag;
        $object->id = 1;
        Tag::shouldReceive('create')->once()->andReturn($object);
        $input = array('tag' => 'test', 'slug' => 'test');
        $this->call('POST', 'admin/tags', $input);
        $this->assertRedirectedToRoute('admin.tags.edit', array('id' => 1));
    }

    public function testStoreSuccessWithRedirectToList()
    {
        $object = new Tag;
        $object->id = 1;
        Tag::shouldReceive('create')->once()->andReturn($object);
        $input = array('tag' => 'test', 'slug' => 'test', 'exit' => true);
        $this->call('POST', 'admin/tags', $input);
        $this->assertRedirectedToRoute('admin.tags.index');
    }

}
