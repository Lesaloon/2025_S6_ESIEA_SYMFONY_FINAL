#!/bin/bash

BASE_URL="http://localhost:8000"
USER_TOKEN=""

login() {
  echo -e "\n🔐 Connexion utilisateur..."
  # read -p "Email: " email
  # read -s -p "Mot de passe: " password
  email="user@airsoft.local"
  password="user123"
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
  get_gear_info $id

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

create_maintenance() {
  echo -e "\n🔧 Création d'une maintenance"
  read -p "ID de l'équipement: " gear_id
  get_gear_info $gear_id

  read -p "Nom de la maintenance: " name
  read -p "Date de la maintenance (YYYY-MM-DD): " date
  read -p "Description: " description


  CREATE_RESPONSE=$(curl -s -X POST "$BASE_URL/api/gear/$gear_id/maintenance" \
    -H "Authorization: Bearer $USER_TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"date\":\"$date\",\"description\":\"$description\",\"name\":\"$name\"}")

  echo "$CREATE_RESPONSE"
}

# takes id as argument
get_gear_info() {
  id=$1
  if [ -z "$id" ]; then
    echo -e "\e[31m❌ ID d'équipement manquant\e[0m"
    return
  fi
  echo -e "\n🔍 Informations sur l'équipement avec ID $id"
  GEAR_INFO=$(curl -s -X GET "$BASE_URL/api/gear/$id" \
    -H "Authorization: Bearer $USER_TOKEN")
  echo "$GEAR_INFO"
  GEAR_NAME=$(echo "$GEAR_INFO" | sed -n 's/.*"name":"\([^"]*\)".*/\1/p')
  GEAR_TYPE=$(echo "$GEAR_INFO" | sed -n 's/.*"type":"\([^"]*\)".*/\1/p')
  GEAR_BRAND=$(echo "$GEAR_INFO" | sed -n 's/.*"brand":"\([^"]*\)".*/\1/p')
  GEAR_MAINTENANCE=$(echo "$GEAR_INFO" | sed -n 's/.*"maintenance":"\([^"]*\)".*/\1/p')
  echo -e "\n📦 Détails de l'équipement :"
  echo -e "\e[33mNom: $GEAR_NAME\e[0m"
  echo -e "\e[33mType: $GEAR_TYPE\e[0m"
  echo -e "\e[33mMarque: $GEAR_BRAND\e[0m"
  echo -e "\e[33mMaintenance: $GEAR_MAINTENANCE\e[0m"
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
  echo "[7] Créer une maintenance"
  echo "[0] Quitter"

  read -p "Choix: " choice

  case $choice in
    1) login ;;
    2) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || list_gear ;;
    3) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || create_gear ;;
    4) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || edit_gear ;;
    5) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || delete_gear ;;
	6) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || view_profile ;;
	7) [ -z "$USER_TOKEN" ] && echo -e "\e[33mVeuillez vous connecter d'abord.\e[0m" || create_maintenance ;;
    0) echo "👋 À bientôt !"; exit 0 ;;
    *) echo "❌ Choix invalide" ;;
  esac
done
