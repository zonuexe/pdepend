<?php
/**
 * This file is part of PHP_Depend.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008-2009, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  QualityAssurance
 * @package   PHP_Depend
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

require_once dirname(__FILE__) . '/../AbstractTest.php';

require_once 'PHP/Depend/Code/Class.php';
require_once 'PHP/Depend/Code/Property.php';

/**
 * Test case for the code property class.
 *
 * @category  QualityAssurance
 * @package   PHP_Depend
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class PHP_Depend_Code_PropertyTest extends PHP_Depend_AbstractTest
{
    /**
     * Tests that {@link PHP_Depend_Code_Property::setModifiers()} fails with
     * an exception for invalid visibility values.
     *
     * @return void
     */
    public function testSetModifiersWithInvalidVisibilityTypeFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $property = new PHP_Depend_Code_Property('$pdepend');
        $property->setModifiers(-1);
    }
    
    /**
     * Tests that {@link PHP_Depend_Code_Property::setModifiers()} only accepts
     * the first set value, later method calls will be ignored.
     *
     * @return void
     */
    public function testSetModifiersOnlyAcceptsTheFirstValue()
    {
        $property = new PHP_Depend_Code_Property('$pdepend');
        $this->assertFalse($property->isPublic());
        $property->setModifiers(PHP_Depend_ConstantsI::IS_PUBLIC);
        $this->assertTrue($property->isPublic());
        $property->setModifiers(PHP_Depend_ConstantsI::IS_PRIVATE);
        $this->assertTrue($property->isPublic());
        $this->assertFalse($property->isPrivate());
    }

    /**
     * Tests that the {@link PHP_Depend_Code_Property::setModifiers()} marks the
     * property is as static.
     *
     * @return void
     */
    public function testSetModifiersMarksPropertyAsStatic()
    {
        $property = new PHP_Depend_Code_Property('$pdepend');
        $this->assertFalse($property->isStatic());

        $property->setModifiers(PHP_Depend_ConstantsI::IS_PRIVATE
                              | PHP_Depend_ConstantsI::IS_STATIC);
        $this->assertTrue($property->isStatic());
    }
    
    /**
     * Tests the default behaviour of the <b>setParent()</b> and <b>getParent()</b>
     * methods.
     *
     * @return void
     */
    public function testSetParentWithNullResetsParentReference()
    {
        $class = new PHP_Depend_Code_Class('clazz');
        
        $property = new PHP_Depend_Code_Property('$pdepend');
        $this->assertNull($property->getParent());
        $property->setParent($class);
        $this->assertSame($class, $property->getParent());
        $property->setParent();
        $this->assertNull($property->getParent());
    }


    /**
     * Tests that build interface updates the source file information for null
     * values.
     *
     * @return void
     */
    public function testSetSourceFileInformationForNullValue()
    {
        $item = new PHP_Depend_Code_Property('$pdepend');
        $file = new PHP_Depend_Code_File(__FILE__);

        $this->assertNull($item->getSourceFile());
        $item->setSourceFile($file);
        $this->assertSame($file, $item->getSourceFile());
    }

    /**
     * Tests that the build interface method doesn't update an existing source
     * file info.
     *
     * @return void
     */
    public function testDoesntSetSourceFileInformationForNotNullValue()
    {
        $item = new PHP_Depend_Code_Property('$pdepend');
        $file = new PHP_Depend_Code_File(__FILE__);

        $item->setSourceFile($file);
        $item->setSourceFile(new PHP_Depend_Code_File('HelloWorld.php'));

        $this->assertSame($file, $item->getSourceFile());
    }
}