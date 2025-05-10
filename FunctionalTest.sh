#!/bin/bash

BASE_URL="http://localhost:8000"
USER_TOKEN=""

login() {
  echo -e "\n🔐 Connexion utilisateur..."
  read -p "Email: " email
  read -s -p "Mot de passe: " password
  echo ""

#   [ -z "$email" ] && $($email="user@airsoft.local")
#   [ -z "$password" ] && $($password="user123")
  LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/api/login \
    -H "Content-Type: application/json" \
    -d "{\"email\":\"$email\",\"password\":\"$password\"}")

  USER_TOKEN=$(echo "$LOGIN_RESPONSE" | sed -n 's/.*"token":"\([^"]*\)".*/\1/p')

  if [ -z "$USER_TOKEN" ]; then
    echo -e "\e[31m❌ Connexion échouée\e[0m"
    echo "$LOGIN_RESPONSE"
    USER_TOKEN=""
  else
    echo -e "\e[32m✅ Connecté avec succès\e[0m"
  fi
}

list_gear() {
  echo -e "\n📦 Liste de votre matériel :"
  curl -s -X GET "$BASE_URL/api/gear" \
    -H "Authorization: Bearer $USER_TOKEN"
}

create_gear() {
  echo -e "\n➕ Création d'un équipement"
  read -p "Nom: " name
  read -p "Type: " type
  read -p "Marque (optionnel): " brand

  CREATE_RESPONSE=$(curl -s -X POST "$BASE_URL/api/gear" \
    -H "Authorization: Bearer $USER_TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"name\":\"$name\",\"type\":\"$type\",\"brand\":\"$brand\"}")

  echo "$CREATE_RESPONSE"
}

edit_gear() {
  read -p "ID du matériel à modifier: " id

  echo -e "\n✏️ Modification de l'équipement avec ID $id"
  CURRENT_GEAR=$(curl -s -X GET "$BASE_URL/api/gear/$id" \
  -H "Authorization: Bearer $USER_TOKEN")
  CURRENT_GEAR_NAME=$(echo "$CURRENT_GEAR" | sed -n 's/.*"name":"\([^"]*\)".*/\1/p')
  CURRENT_GEAR_TYPE=$(echo "$CURRENT_GEAR" | sed -n 's/.*"type":"\([^"]*\)".*/\1/p')
  CURRENT_GEAR_BRAND=$(echo "$CURRENT_GEAR" | sed -n 's/.*"brand":"\([^"]*\)".*/\1/p')
  echo "$CURRENT_GEAR"
  echo -e "\n📦 Détails actuels :"
  echo -e "\e[33mNom actuel: $CURRENT_GEAR_NAME\e[0m"
  echo -e "\e[33mType actuel: $CURRENT_GEAR_TYPE\e[0m"
  echo -e "\e[33mMarque actuelle: $CURRENT_GEAR_BRAND\e[0m"
  echo -e "\n🔄 Entrez les nouvelles informations (laisser vide pour conserver l'ancienne valeur) :"

  read -p "Nouveau nom: " name
  read -p "Nouveau type: " type
  read -p "Nouvelle marque: " brand

  # Si le champ est vide, conserver l'ancienne valeur
  [ -z "$name" ] && name=$CURRENT_GEAR_NAME
  [ -z "$type" ] && type=$CURRENT_GEAR_TYPE
  [ -z "$brand" ] && brand=$CURRENT_GEAR_BRAND
  EDIT_RESPONSE=$(curl -s -X PUT "$BASE_URL/api/gear/$id" \
    -H "Authorization: Bearer $USER_TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"name\":\"$name\",\"type\":\"$type\",\"brand\":\"$brand\"}")

  echo "$EDIT_RESPONSE"
}

delete_gear() {
  read -p "ID du matériel à supprimer: " id

  DELETE_RESPONSE=$(curl -s -X DELETE "$BASE_URL/api/gear/$id" \
    -H "Authorization: Bearer $USER_TOKEN")

  echo "$DELETE_RESPONSE"
}

view_profile() {
  echo -e "\n👤 Informations utilisateur :"
  curl -s -X GET "$BASE_URL/api/me" \
    -H "Authorization: Bearer $USER_TOKEN"
}


# --- Boucle principale ---
while true; do
  echo -e "\n🎛️  Menu - Gestion du matériel"
  echo "[1] Se connecter"
  echo "[2] Lister mes équipements"
  echo "[3] Créer un équipement"
  echo "[4] Modifier un équipement"
  echo "[5] Supprimer un équipement"
  echo "[6] Voir mes informations"
  echo "[0] Quitter"

  read -p "Choix: " choice

  case $choice in
    1) login ;;
    2) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || list_gear ;;
    3) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || create_gear ;;
    4) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || edit_gear ;;
    5) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || delete_gear ;;
	6) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || view_profile ;;
    0) echo "👋 À bientôt !"; exit 0 ;;
    *) echo "❌ Choix invalide" ;;
  esac
done
