#VRML V2.0 utf8

Transform {
  children [
    DEF ME_<?=$_POST['name']?> Shape {
      appearance Appearance {
        material Material {
          ambientIntensity 0.1667
          diffuseColor 0.8 0.8 0.8
          specularColor 0.4012 0.4012 0.4012
          emissiveColor 0 0 0
          shininess 0.0977
          transparency 0
        }
        texture NULL
        textureTransform NULL
      }
      geometry IndexedFaceSet {
        color NULL
        coord Coordinate {
          point [
<?=$_POST['points']?>
          ]
        }
        colorIndex [ ]
        coordIndex [
<?=$_POST['faces']?>
        ]
        normal NULL
        creaseAngle 0
        solid TRUE
      }
    }
  ]
}