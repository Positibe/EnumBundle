PositibeEnumBundle
==================

This bundle give a simple way to have all enum type in to table.

Introduction
------------

When we are working with many classes that only have one field like 'name' or 'code' e.j. gender, category, state, etc.
 We frequently create a class/table for each one, but in long web site we will have many of this classes so the schema could be huge.

With this bundle you can simple have a relation we Enum class and define the EnumType class for it.

Example
-------

    #app/config/config.yml
    #...
    positibe_enum:
        enum_types:
            sex:
                _name: Sexo #Optional name description
                male: Masculino
                female: Femenino
            organism: